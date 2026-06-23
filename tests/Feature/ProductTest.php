<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route; 
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_customer_can_view_product_list()
    {
        $category = Category::factory()->create(['name' => 'Alat Tulis']);
        $product1 = Product::factory()->create(['name' => 'Buku Sinar Dunia', 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['name' => 'Pulpen Faster', 'category_id' => $category->id]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.home');
        $response->assertSee($product1->name);
        $response->assertSee($product2->name);
    }

    public function test_customer_can_search_product()
    {
        $targetProduct = Product::factory()->create(['name' => 'Tumbler Custom']);
        $otherProduct = Product::factory()->create(['name' => 'Payung Lipat']);

        $response = $this->get(route('products.index', ['search' => 'Tumbler']));

        $response->assertStatus(200);
        $response->assertSee($targetProduct->name);
        $response->assertDontSee($otherProduct->name);
    }

 public function test_customer_can_view_product_detail()
    {
        \Illuminate\Support\Facades\URL::defaults(['product' => 1]);

        $product = Product::factory()->create([
            'name' => 'Jaket Perusahaan',
            'price' => 150000
        ]);

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        
        $response->assertViewHas('product');
    }

    public function test_customer_can_filter_products_by_category()
    {
        $categoryOffice = Category::factory()->create(['name' => 'Office']);
        $categorySouvenir = Category::factory()->create(['name' => 'Souvenir']);

        $officeProduct = Product::factory()->create(['name' => 'Kertas A4', 'category_id' => $categoryOffice->id]);
        $souvenirProduct = Product::factory()->create(['name' => 'Gantungan Kunci', 'category_id' => $categorySouvenir->id]);

        $response = $this->get(route('products.index', ['category' => 'Office']));

        $response->assertStatus(200);
        $response->assertSee($officeProduct->name);
        $response->assertDontSee($souvenirProduct->name);
    }

    public function test_system_shows_empty_message_on_wrong_search_keyword()
    {
        Product::factory()->create(['name' => 'Jam Dinding']);

        $response = $this->get(route('products.index', ['search' => 'Jma Dinding']));

        $response->assertStatus(200);
        $response->assertSee('Belum ada produk terbaru yang tersedia.');
    }

    public function test_system_shows_empty_message_on_empty_category()
    {
        $emptyCategory = Category::factory()->create(['name' => 'Eksklusif']);
        $filledCategory = Category::factory()->create(['name' => 'Reguler']);
        
        Product::factory()->create(['name' => 'Mug Cetak', 'category_id' => $filledCategory->id]);

        $response = $this->get(route('products.index', ['category' => 'Eksklusif']));

        $response->assertStatus(200);
        $response->assertSee('Belum ada produk terbaru yang tersedia.');
        $response->assertDontSee('Mug Cetak');
    }
}