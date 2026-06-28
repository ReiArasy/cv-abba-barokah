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
    | FITUR: CUSTOMER LANDING PAGE & NAVIGATION ACCESS
    |--------------------------------------------------------------------------
    */

    /** TC-LP-001: Memastikan Proteksi Akses Fitur untuk Pengguna yang Belum Login (Guest) via Navbar */
    public function test_tclp001_guest_cannot_access_protected_navbar_menus_directly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Memastikan fungsi JavaScript peringatanLogin() dan potongan teks SweetAlert tersedia
        $response->assertSee('peringatanLogin');
        $response->assertSee('Silahkan Login / Registrasi');
    }

    /** TC-LP-002: Memastikan Proteksi Tombol Order Produk Memicu SweetAlert bagi Pengguna Belum Login */
    public function test_tclp002_guest_cannot_access_order_produk_without_authentication()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Memastikan potongan pesan pop-up terikat di halaman utama
        $response->assertSee('Silahkan Login / Registrasi');
    }

    /** TC-LP-003: Memastikan Tautan Navigasi Contact Berfungsi Mengarahkan Halaman ke Footer */
    public function test_tclp003_landing_page_contains_contact_footer_anchor()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="main-footer"', false);
    }

    /** TC-LP-004: Memastikan Fitur Pencarian Produk Dapat Digunakan dengan Kata Kunci Valid */
    public function test_tclp004_customer_dapat_mencari_produk_dengan_kata_kunci_valid()
    {
        // 1. Buat User palsu agar bisa menembus middleware auth rute /products
        $customer = User::create([
            'name' => 'Abdul Hakim Al Baihaqy',
            'username' => 'HakimSearch',
            'email' => 'hakimsearch@gmail.com',
            'phone' => '085785964671',
            'password' => bcrypt('Surabaya2026!'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);
        $this->actingAs($customer);

        // 2. Mengatasi NOT NULL constraint untuk categories
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Souvenir & Merchandise',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Membuat data tiruan produk
        Product::create([
            'category_id' => $categoryId,
            'name' => 'Souvenir Gelas Eksklusif',
            'price' => 15000,
            'stock' => 50,
            'image' => ['souvenir.jpg'],
            'description' => 'Souvenir gelas berkualitas dari ABBA Barokah'
        ]);

        // 4. Mengirimkan request pencarian ke rute katalog setelah login
        $response = $this->get('/products?search=Souvenir');
        $response->assertStatus(200);
        
        $response->assertSee('Souvenir Gelas Eksklusif');
    }

    /** TC-LP-005-N: Melakukan Pencarian dengan Kata Kunci Produk yang Tidak Ada di Database */
    public function test_tclp005n_customer_mencari_produk_yang_tidak_terdaftar_di_database()
    {
        // Mencari kata kunci acak pada kolom pencarian halaman utama / katalog fallback
        $response = $this->get('/?search=Kursi');
        $response->assertStatus(200);
        
        $response->assertSee('Belum ada produk terbaru yang tersedia');
    }

    /** TC-LP-006-A: Memastikan Akses Menu Langsung Terbuka setelah Login Sukses (Customer) */
    public function test_tclp006a_authenticated_customer_bisa_mengakses_semua_fitur()
    {
        $customer = User::create([
            'name' => 'Abdul Hakim Al Baihaqy',
            'username' => 'HakimCustomer',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Surabaya2026!'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        $this->actingAs($customer);

        $responseProduct = $this->get('/products');
        $responseProduct->assertStatus(200);
    }
}