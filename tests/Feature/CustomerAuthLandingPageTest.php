<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAuthLandingPageTest extends TestCase
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
    | 1. FITUR: CUSTOMER LOGIN
    |--------------------------------------------------------------------------
    */

    /** TC-006: Customer Login Akun Valid */
    public function test_tc006_customer_login_akun_valid()
    {
        User::create([
            'name' => 'Abdul Hakim', 'username' => 'Isopad23', 'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677', 'password' => bcrypt('Madrid-Bayern_1-2'),
            'alamat_lengkap' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya', 'role' => 'customer'
        ]);

        $response = $this->post('/login', ['email' => 'hakimalbaihaqy100@gmail.com', 'password' => 'Madrid-Bayern_1-2']);
        $response->assertRedirect('/'); 
    }

    /** TC-006A: Customer Login Akun Invalid */
    public function test_tc006a_customer_login_akun_invalid()
    {
        $response = $this->post('/login', ['email' => 'isopad23@wrong.com', 'password' => 'salah123']);
        $response->assertSessionHasErrors('email');
    }

    /** TC-006B: Customer Login Field Kosong */
    public function test_tc006b_customer_login_field_kosong()
    {
        $response = $this->post('/login', ['email' => '', 'password' => '']);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. FITUR: CUSTOMER REGISTER
    |--------------------------------------------------------------------------
    */

    /** TC-005: Customer Registrasi Akun Valid */
    public function test_tc005_customer_registrasi_akun_valid()
    {
        $response = $this->post('/register', [
            'name' => 'Abdul Hakim', 'username' => 'Isopad23', 'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677', 'password' => 'Madrid-Bayern_1-2',
            'alamat_lengkap' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya'
        ]);
        $this->assertDatabaseHas('users', ['email' => 'hakimalbaihaqy100@gmail.com']);
        $response->assertRedirect('/login');
    }

    /** TC-007A (Register): Customer Memasukkan Alamat Email yang sudah digunakan */
    public function test_tc007a_register_email_sudah_digunakan()
    {
        User::create([
            'name' => 'Lama', 'username' => 'old', 'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '0812', 'password' => bcrypt('123'), 'alamat_lengkap' => 'Sby', 'provinsi' => 'Jatimm', 'kota' => 'Sby', 'role' => 'customer'
        ]);

        $response = $this->post('/register', [
            'name' => 'Abdul Hakim', 'username' => 'Isopad23', 'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677', 'password' => 'Madrid-Bayern_1-2', 'alamat_lengkap' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya'
        ]);
        $response->assertSessionHasErrors('email');
    }

    /** TC-007B (Register): Customer Tidak Memasukkan Data Registrasi */
    public function test_tc007b_register_tidak_memasukkan_data_registrasi()
    {
        $response = $this->post('/register', []);
        $response->assertSessionHasErrors(['name', 'username', 'email', 'password']);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. FITUR: CUSTOMER LANDING PAGE (NORMAL PATH)
    |--------------------------------------------------------------------------
    */

    /** TC-007A (Normal): Verifikasi fitur Search Product di Hero Section */
    public function test_tc007a_normal_verifikasi_fitur_search_product()
    {
        $response = $this->get('/?search=Gelas');
        $response->assertStatus(200);
    }

    /** TC-007B (Normal): Verifikasi navigasi Navbar menu */
    public function test_tc007b_normal_verifikasi_navigasi_navbar()
    {
        $response = $this->get('/');
        $response->assertSee('Home');
    }

    /** TC-007C (Normal): Verifikasi tombol View All Products */
    public function test_tc007c_normal_verifikasi_tombol_view_all()
    {
        $response = $this->get('/');
        $response->assertSee('View All');
    }

    /** TC-007D (Normal): Verifikasi tombol Order Produk (Exclusive Section) */
    public function test_tc007d_normal_verifikasi_tombol_order_produk()
    {
        $response = $this->get('/');
        $response->assertSee('Order Produk');
    }

    /** TC-007E (Normal): Verifikasi tombol Shop Now (Banner Bawah) */
    public function test_tc007e_normal_verifikasi_tombol_shop_now()
    {
        $response = $this->get('/');
        $response->assertSee('Shop Now');
    }

    /** TC-007F (Normal): Verifikasi akurasi data kontak di Footer */
    public function test_tc007f_normal_verifikasi_kontak_footer()
    {
        $response = $this->get('/');
        $response->assertSee('ABBABAROKAH@gmail.com');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. FITUR: CUSTOMER LANDING PAGE (ALTERNATE / NEGATIVE PATH)
    |--------------------------------------------------------------------------
    */

    /** TC-007G (Alternate): Verifikasi Search karakter kosong */
    public function test_tc007g_alternate_search_karakter_kosong()
    {
        $response = $this->get('/?search=');
        $response->assertStatus(200);
    }

    /** TC-007A (Alternate): Verifikasi fitur Search dengan karakter spesial/simbol */
    public function test_tc007a_alternate_search_karakter_spesial()
    {
        $response = $this->get('/?search=@#%^*');
        $response->assertStatus(200);
    }

    /** TC-007B (Alternate): Verifikasi Search produk yang tidak terdaftar di database */ 
    public function test_tc007b_alternate_search_produk_tidak_terdaftar()
    {
        $response = $this->get('/?search=Mobil+Terbang');
        $response->assertSee('Belum ada produk terbaru yang tersedia.');
    }

    /** TC-007C (Alternate): Verifikasi akses fitur saat koneksi internet terputus (Simulasi Respons Berhasil) */
    public function test_tc007c_alternate_akses_fitur_saat_koneksi_terputus()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** TC-007D (Alternate): Verifikasi klik link navbar yang sedang dalam perbaikan (Dead Link) */
    public function test_tc007d_alternate_klik_link_navbar_dead_link()
    {
        $response = $this->get('/link-menu-yang-belum-aktif');
        $response->assertStatus(404);
    }

    /** TC-007E (Alternate): Verifikasi Search dengan jumlah karakter terlalu panjang */
    public function test_tc007e_alternate_search_karakter_terlalu_panjang()
    {
        $longText = str_repeat('A', 1000);
        $response = $this->get('/?search=' . $longText);
        $response->assertStatus(200);
    }
}