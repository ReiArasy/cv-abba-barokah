<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CartTest extends TestCase
{
    use RefreshDatabase;

     // Helper untuk membuat simulasi user
     
    private function createCustomer()
    {
        return User::factory()->create([
            'role' => 'customer' 
        ]);
    }

     // Helper pembuat produk yang ANTI-ERROR.
     // Menggunakan forceFill agar dipastikan semua kolom terisi di database SQLite.
    private function createProduct($stock = 10, $price = 50000)
    {
        // 1. Buat kategori
        $category = Category::firstOrCreate(
            ['name' => 'Kategori Test'],
            ['is_active' => true]
        );

        // 2. Buat produk dengan forceFill untuk menembus aturan apa pun
        $product = new Product();
        $product->forceFill([
            'category_id' => $category->id,
            'name' => 'Produk Test ' . Str::random(5),
            'price' => $price,
            'stock' => $stock,
            'description' => 'Ini deskripsi produk agar tidak error NOT NULL', // Memastikan terisi
            'image' => 'default.png',
            'is_active' => true,
        ]);
        $product->save();

        return $product;
    }


    // NORMAL FLOW (SKENARIO SUKSES) -> 5 Test

    public function test_pelanggan_bisa_melihat_keranjang_kosong()
    {
        $user = $this->createCustomer();
        $response = $this->actingAs($user)->get(route('cart.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('cart.index'); 
    }

    public function test_pelanggan_bisa_menambahkan_produk_baru_ke_keranjang()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        $response = $this->actingAs($user)->post(route('cart.add', $product->id), [
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Produk berhasil dimasukkan ke keranjang belanja.');

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_pelanggan_bisa_menambahkan_produk_yang_sama_untuk_tambah_kuantitas()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 2]);
        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 3]);

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5, 
        ]);
    }

    public function test_pelanggan_bisa_memperbarui_jumlah_kuantitas_di_keranjang()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 1]);

        $response = $this->from(route('cart.index'))
                         ->actingAs($user)
                         ->patch(route('cart.update', $product->id), [
            'quantity' => 5,
        ]);

        $response->assertStatus(302); 
        $response->assertSessionHas('success', 'Jumlah belanjaan berhasil diperbarui.');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_pelanggan_bisa_menghapus_produk_dari_keranjang()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 2]);

        $response = $this->from(route('cart.index'))
                         ->actingAs($user)
                         ->delete(route('cart.remove', $product->id));

        $response->assertStatus(302); 
        $response->assertSessionHas('success', 'Produk berhasil dikeluarkan dari keranjang.');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }

    
    // ALTERNATE FLOW (SKENARIO GAGAL / VALIDASI) -> 5 Test

    public function test_user_belum_login_tidak_bisa_mengakses_keranjang()
    {
        $response = $this->get(route('cart.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_pelanggan_tidak_bisa_menambahkan_produk_melebihi_stok()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(5, 50000); // Stok dibuat hanya 5

        $response = $this->actingAs($user)->post(route('cart.add', $product->id), [
            'quantity' => 10,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_pelanggan_tidak_bisa_menambah_produk_sama_jika_totalnya_melebihi_stok()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(5, 50000); // Stok 5

        // Tambah 3
        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 3]);
        // Tambah 3 lagi (Total 6 -> Harus gagal)
        $response = $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 3]);

        $response->assertSessionHas('error', 'Stok komoditas produk tidak mencukupi batas maksimal.');
        
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3, // Tetap 3, tidak menjadi 6
        ]);
    }

    public function test_pelanggan_tidak_bisa_memperbarui_kuantitas_melebihi_stok()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(5, 50000); // Stok 5

        $this->actingAs($user)->post(route('cart.add', $product->id), ['quantity' => 2]);

        $response = $this->actingAs($user)->patch(route('cart.update', $product->id), [
            'quantity' => 10,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_pelanggan_tidak_bisa_menambahkan_produk_dengan_kuantitas_tidak_valid()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        // 1. Mencoba memasukkan quantity 0 (Tidak boleh kurang dari 1)
        $response1 = $this->actingAs($user)->post(route('cart.add', $product->id), [
            'quantity' => 0,
        ]);
        $response1->assertSessionHasErrors('quantity');

        // 2. Mencoba memasukkan angka minus
        $response2 = $this->actingAs($user)->post(route('cart.add', $product->id), [
            'quantity' => -5,
        ]);
        $response2->assertSessionHasErrors('quantity');

        // Pastikan keranjang tidak pernah terbuat di database
        $this->assertDatabaseCount('carts', 0);
    }
}