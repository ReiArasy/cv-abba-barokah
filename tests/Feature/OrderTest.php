<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer()
    {
        return User::factory()->create([
            'role' => 'customer'
        ]);
    }

    private function createProduct($stock = 10, $price = 50000)
    {
        $category = Category::firstOrCreate(
            ['name' => 'Kategori Test'],
            ['is_active' => true]
        );

        $product = new Product();
        $product->forceFill([
            'category_id' => $category->id,
            'name' => 'Produk Test ' . Str::random(5),
            'price' => $price,
            'stock' => $stock,
            'description' => 'Deskripsi dummy untuk testing order',
            'image' => 'default.png',
            'is_active' => true,
        ]);
        $product->save();

        return $product;
    }
 // Normal Flow

    public function test_pelanggan_berhasil_melakukan_checkout_langsung()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        $response = $this->actingAs($user)->post(route('orders.direct'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $response->assertRedirect(route('orders.show', ['order' => $order->code]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'total_price' => 100000]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 8]); 
    }

    public function test_pelanggan_berhasil_melakukan_checkout_dari_keranjang()
    {
        $user = $this->createCustomer();
        $product1 = $this->createProduct(10, 20000);
        $product2 = $this->createProduct(5, 30000);


        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create(['cart_id' => $cart->id, 'user_id' => $user->id, 'product_id' => $product1->id, 'quantity' => 2, 'price' => 20000]);
        CartItem::create(['cart_id' => $cart->id, 'user_id' => $user->id, 'product_id' => $product2->id, 'quantity' => 1, 'price' => 30000]);

        $response = $this->actingAs($user)->post(route('checkout'));

        $order = Order::where('user_id', $user->id)->first();

        $response->assertRedirect(route('orders.show', ['order' => $order->code]));
        
    
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'total_price' => 70000]);
        $this->assertDatabaseEmpty('cart_items'); 
    }

    public function test_pelanggan_bisa_melihat_riwayat_pesanan()
    {
        $user = $this->createCustomer();
        Order::create([
            'user_id' => $user->id,
            'code' => 'ORD-2026-ABCDEF',
            'total_price' => 50000,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('orders.history');
        $response->assertSee('ORD-2026-ABCDEF'); 
    }

    public function test_pelanggan_bisa_melihat_detail_pesanan()
    {
        $user = $this->createCustomer();
        $order = Order::create([
            'user_id' => $user->id,
            'code' => 'ORD-2026-BYPASS',
            'total_price' => 50000,
            'status' => 'paid',
            'payment_status' => 'paid' 
        ]);

        $response = $this->actingAs($user)->get(route('orders.show', ['order' => $order->code]));

        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
    }

// Alternate Flow

        public function test_checkout_langsung_gagal_jika_stok_tidak_mencukupi()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(3, 50000); // Stok cuma 3

        $response = $this->actingAs($user)->post(route('orders.direct'), [
            'product_id' => $product->id,
            'quantity' => 10, 
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Stok produk tidak mencukupi.');
        $this->assertDatabaseCount('orders', 0); // Pesanan tidak terbuat
    }

    public function test_checkout_langsung_gagal_jika_input_tidak_valid()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(10, 50000);

        // Mencoba input quantity 0
        $response = $this->actingAs($user)->post(route('orders.direct'), [
            'product_id' => $product->id,
            'quantity' => 0, 
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function test_checkout_dari_keranjang_gagal_jika_melebihi_stok()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct(5, 50000); 

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id'    => $cart->id,
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'quantity'   => 5,
            'price'      => $product->price,
        ]);
        
        $product->update(['stock' => 2]);

        // 3. Eksekusi: Melakukan checkout
        $response = $this->actingAs($user)->post(route('checkout'));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 2
        ]);
        
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5
        ]);
    }

    
}