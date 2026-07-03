<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerLandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (method_exists($this, 'withoutVite')) {
            $this->withoutVite();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR: LANDING PAGE CUSTOMER (Website CV ABBA Barokah)
    |--------------------------------------------------------------------------
    */

    /**
     * No. 1: TC-011 A
     * Scenario: Klik menu Product, About us, Purchase pada navbar (GUEST)
     */
    public function test_tc011a_guest_cannot_access_protected_navbar_menus_directly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Memastikan memicu fungsi peringatanLogin()
        $response->assertSee('peringatanLogin');
        
        // Memastikan potongan komponen kalimat teks SweetAlert termuat di halaman utama
        $response->assertSee('Akses Terbatas!');
        $response->assertSee('Silahkan Login / Registrasi Terlebih Dahulu!');
    }

    /**
     * No. 2: TC-011 B
     * Scenario: Scroll ke section penawaran eksklusif, Klik tombol Order Produk (GUEST)
     */
    public function test_tc011b_guest_cannot_access_order_produk_without_authentication()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Memastikan sistem memicu pop-up peringatan SweetAlert yang sama agar user melakukan login terlebih dahulu
        $response->assertSee('Akses Terbatas!');
        $response->assertSee('Silahkan Login / Registrasi Terlebih Dahulu!');
    }

    /**
     * No. 3: TC-011 C
     * Scenario: Klik menu Contact pada navbar
     */
    public function test_tc011c_landing_page_contains_contact_footer_anchor()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Memastikan halaman memiliki section footer penampung nomor kontak (#main-footer) untuk mekanisme smooth scroll
        $response->assertSee('id="main-footer"', false);
    }

    /**
     * No. 4: TC-011 D
     * Scenario: Customer login terlebih dahulu, Input kata kunci "Meja", Tekan Enter
     */
    public function test_tc011d_customer_dapat_mencari_produk_dengan_kata_kunci_meja()
    {
        // 1. Customer diwajibkan login terlebih dahulu
        $customer = User::create([
            'name' => 'Abdul Hakim Al Baihaqy',
            'username' => 'HakimTester',
            'email' => 'hakimtester@gmail.com',
            'phone' => '085785964671',
            'password' => bcrypt('Surabaya2026!'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);
        $this->actingAs($customer);

        // Menyiapkan data kategori agar tidak melanggar data integrity constraint database
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Furniture',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Membuat data tiruan produk sesuai dengan kata kunci di tabel ("Meja")
        Product::create([
            'category_id' => $categoryId,
            'name' => 'Meja Kantor Abba',
            'price' => 500000,
            'stock' => 15,
            'image' => ['meja_kantor.jpg'],
            'description' => 'Meja berkualitas tinggi untuk kebutuhan kantor.'
        ]);

        // 2. Input kata kunci produk "Meja" pada kolom search dan jalankan request halaman produk
        $response = $this->get('/products?search=Meja');
        $response->assertStatus(200);
        
        // Ekspektasi: Menyaring dan menampilkan produk yang sesuai
        $response->assertSee('Meja Kantor Abba');
    }

    /**
     * No. 5: TC-008-A
     * Scenario: Customer Diwajibkan Login Terlebih Dahulu, Input kata kunci acak "Kursi", Tekan Enter
     */
    public function test_tc008a_customer_mencari_kata_kunci_acak_kursi_yang_tidak_tersedia()
    {
        // 1. Customer diwajibkan login terlebih dahulu
        $customer = User::create([
            'name' => 'Abdul Hakim Al Baihaqy',
            'username' => 'HakimTester2',
            'email' => 'hakimtester2@gmail.com',
            'phone' => '085785964672',
            'password' => bcrypt('Surabaya2026!'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);
        $this->actingAs($customer);

        // 2. Input kata kunci acak "Kursi" pada kolom pencarian
        $response = $this->get('/products?search=Kursi');
        $response->assertStatus(200);
        
        // Ekspektasi: Menampilkan pesan fallback sesuai visual tabel
        $response->assertSee('Belum ada produk terbaru yang tersedia');
    }
}