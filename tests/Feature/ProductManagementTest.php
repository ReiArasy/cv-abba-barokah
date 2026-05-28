<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Resources\ProductResource;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // SETUP: Membuat admin user hardcoded
        $this->admin = User::create([
            'name'     => 'Abba Admin',
            'email'    => 'abba@gmail.com',
            'password' => bcrypt('abba123'),
            'role'     => 'admin',
        ]);

        // SETUP: Membuat kategori default
        $this->category = Category::create([
            'name'      => 'Kategori Utama',
            'is_active' => true,
        ]);
    }

    
    // =========================================================================
    // TEST CASE ALTERNATE (Negative Test)
    // =========================================================================

    /**
     * TC-002A1: Admin gagal menyimpan produk karena nama kosong
     */
    public function test_admin_cannot_create_product_with_empty_name()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => '',
                'price'       => 500000,
                'stock'       => 20,
                'description' => 'Meja Premium Berbahan Kualitas',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors(['name']);
        $this->assertDatabaseMissing('products', [
            'price'       => 500000,
            'stock'       => 20,
            'description' => 'Meja Premium Berbahan Kualitas',
        ]);
    }

    /**
     * TC-002A2: Admin gagal menyimpan produk karena stok negatif
     */
    public function test_admin_cannot_create_product_with_negative_stock()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => 'Meja Premium',
                'price'       => 500000,
                'stock'       => -20,
                'description' => 'Meja Premium Berbahan Kualitas',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors(['stock']);
        $this->assertDatabaseMissing('products', [
            'name'  => 'Meja Premium',
            'stock' => -20,
        ]);
    }

    /**
     * TC-002A3: Admin gagal menyimpan produk karena harga negatif
     * CATATAN: Bug detection test — backend belum memvalidasi harga negatif (Status FAIL)
     */
    public function test_admin_cannot_create_product_with_negative_price()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => 'Meja Premium',
                'price'       => -500000,
                'stock'       => 20,
                'description' => 'Meja Premium Berbahan Kualitas',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors(['price']);
        $this->assertDatabaseMissing('products', [
            'name'  => 'Meja Premium',
            'price' => -500000,
        ]);
    }

    /**
     * TC-002A5: Admin gagal menyimpan produk karena deskripsi kosong
     * CATATAN: Bug detection test — sistem masih menyimpan walau deskripsi kosong (Status FAIL)
     */
    public function test_admin_cannot_create_product_with_empty_description()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => 'Meja Premium',
                'price'       => 500000,
                'stock'       => 20,
                'description' => '',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors(['description']);
        $this->assertDatabaseMissing('products', [
            'name'  => 'Meja Premium',
            'price' => 500000,
            'stock' => 20,
        ]);
    }

    /**
     * TC-002A6: Admin gagal menyimpan produk karena gambar kosong
     * CATATAN: Bug detection test — sistem masih menyimpan walau gambar kosong (Status FAIL)
     */
    public function test_admin_cannot_create_product_with_empty_image()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => 'Meja Premium',
                'price'       => 500000,
                'stock'       => 20,
                'description' => 'Meja Premium',
                'image'       => [],
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors(['image']);
        $this->assertDatabaseMissing('products', [
            'name'  => 'Meja Premium',
            'price' => 500000,
            'stock' => 20,
        ]);
    }

    /**
     * TC-002A7: Admin gagal menyimpan produk karena semua field kosong
     */
    public function test_admin_cannot_create_product_with_all_empty_fields()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $component = Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => '',
                'price'       => '',
                'stock'       => '',
                'description' => '',
                'image'       => [],
                'category_id' => '',
                'is_active'   => true,
            ])
            ->call('create');

        // ASSERTION
        $component->assertHasFormErrors([
            'name',
            'price',
            'stock',
            'description',
            'image',
            'category_id',
        ]);
        $this->assertDatabaseCount('products', 0);
    }

    // TEST CASE NORMAL (Positive Test / Happy Path)

    /**
     * TC-002N1: Admin berhasil mengakses halaman daftar produk
     */
    public function test_admin_can_view_product_list()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        $response = $this->get('/admin/products');

        // ASSERTION
        $response->assertStatus(200);
    }

    /**
     * TC-002N2: Admin berhasil membuat produk baru dengan data valid
     */
    public function test_admin_can_create_product_successfully()
    {
        // SETUP
        $this->actingAs($this->admin);

        // ACTION
        Livewire::test(ProductResource\Pages\CreateProduct::class)
            ->fillForm([
                'name'        => 'Meja Premium',
                'price'       => 500000,
                'stock'       => 20,
                'description' => 'Meja Premium Berbahan Kualitas Tinggi',
                'image'       => 'meja-premium.jpg',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        // ASSERTION
        $this->assertDatabaseHas('products', [
            'name'  => 'Meja Premium',
            'price' => 500000,
            'stock' => 20,
        ]);
    }

    /**
     * TC-002N4: Admin berhasil mengedit produk yang sudah ada
     */
    public function test_admin_can_edit_product_successfully()
    {
        // SETUP
        $this->actingAs($this->admin);
        $product = Product::create([
            'name'        => 'Meja Premium',
            'price'       => 500000,
            'stock'       => 20,
            'description' => 'Deskripsi Awal',
            'image'       => 'meja.jpg',
            'category_id' => $this->category->id,
            'is_active'   => true,
        ]);

        // ACTION
        Livewire::test(ProductResource\Pages\EditProduct::class, [
            'record' => $product->id,
        ])
            ->fillForm([
                'name'        => 'Meja Premium Updated',
                'price'       => 600000,
                'stock'       => 15,
                'description' => 'Deskripsi Updated',
                'image'       => 'meja-updated.jpg',
                'category_id' => $this->category->id,
                'is_active'   => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // ASSERTION
        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'Meja Premium Updated',
            'price' => 600000,
            'stock' => 15,
        ]);
        $this->assertDatabaseMissing('products', [
            'name'  => 'Meja Premium',
            'price' => 500000,
        ]);
    }
}
