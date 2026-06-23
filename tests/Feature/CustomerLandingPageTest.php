<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
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
    | Skenario pengujian untuk memvalidasi elemen utama landing page, proteksi
    | menu autentikasi (@guest / @auth), serta fitur pencarian produk.
    |
    */

    /** TC-LP-001: Memastikan Proteksi Akses Fitur untuk Pengguna yang Belum Login (Guest) via Navbar */
    public function test_tclp001_guest_cannot_access_protected_navbar_menus_directly()
    {
        // Memastikan halaman utama (landing page) dapat diakses oleh guest dengan sukses
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('CV ABBA BAROKAH');

        // Mensimulasikan Guest menembak langsung URL fitur terproteksi (sisi backend middleware)
        // Karena di view menggunakan proteksi @guest, di sisi server harus memastikan dialihkan ke login
        $responseProduct = $this->get('/products');
        $responseProduct->assertRedirect('/login');

        $responseAbout = $this->get('/about-us');
        $responseAbout->assertRedirect('/login');

        $responsePurchase = $this->get('/purchase/history');
        $responsePurchase->assertRedirect('/login');
    }

    /** TC-LP-002: Memastikan Proteksi Tombol Order Produk Mengarahkan Akses Sesuai Middleware */
    public function test_tclp002_guest_cannot_access_order_produk_without_authentication()
    {
        // Mencoba menembak langsung rute katalog produk yang dituju oleh tombol "Order Produk"
        $response = $this->get('/products');

        // Memastikan sistem menolak dan mengarahkan tamu kembali ke halaman login
        $response->assertRedirect('/login');
    }

    /** TC-LP-003: Memastikan Tautan Navigasi Contact Berfungsi Mengarahkan Halaman ke Footer */
    public function test_tclp003_landing_page_contains_contact_footer_anchor()
    {
        // Mengakses halaman landing page
        $response = $this->get('/');

        // Memastikan halaman sukses dimuat
        $response->assertStatus(200);

        // Memastikan terdapat tag ID penampung jangkar smooth-scroll pada footer
        $response->assertSee('id="main-footer"', false);
        $response->assertSee('ABBABAROKAH@gmail.com');
    }

    /** TC-LP-004: Memastikan Fitur Pencarian Produk Dapat Digunakan dengan Kata Kunci Valid */
    public function test_tclp004_customer_dapat_mencari_produk_dengan_kata_kunci_valid()
    {
        // Membuat data produk tiruan di database menggunakan manual array/factory
        Product::create([
            'name' => 'Souvenir Gelas Eksklusif',
            'price' => 15000,
            'image' => ['souvenir.jpg'],
            'description' => 'Souvenir gelas berkualitas dari ABBA Barokah'
        ]);

        // Mengirimkan request GET ke landing page dengan parameter pencarian (search)
        $response = $this->get('/?search=Souvenir');

        // Memastikan status response OK dan menampilkan produk yang dicari
        $response->assertStatus(200);
        $response->assertSee('Souvenir Gelas Eksklusif');
    }

    /** TC-LP-005-N: Melakukan Pencarian dengan Kata Kunci Produk yang Tidak Ada di Database */
    public function test_tclp005n_customer_mencari_produk_yang_tidak_terdaftar_di_database()
    {
        // Mengirimkan request GET dengan kata kunci produk random yang tidak ada di database
        $response = $this->get('/?search=KulkasPolytron123');

        $response->assertStatus(200);
        
        // Memastikan sistem menampilkan pesan fallback/empty state yang sesuai di html blade
        $response->assertSee('Belum ada produk terbaru yang tersedia.');
    }

    /** TC-LP-006-A: Memastikan Akses Menu Langsung Terbuka setelah Login Sukses (Customer) */
    public function test_tclp006a_authenticated_customer_bisa_mengakses_semua_fitur()
    {
        // Membuat user master customer di database
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

        // Bertindak sebagai customer yang sudah sah masuk log (Auth)
        $this->actingAs($customer);

        // Memastikan customer bisa membuka halaman katalog produk
        $responseProduct = $this->get('/products');
        $responseProduct->assertStatus(200);

        // Memastikan customer bisa membuka halaman About Us yang baru dibuat
        $responseAbout = $this->get('/about-us');
        $responseAbout->assertStatus(200);
    }
}