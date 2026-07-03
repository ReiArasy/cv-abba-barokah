<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRegisterTest extends TestCase
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
    | FITUR: CUSTOMER REGISTER
    |--------------------------------------------------------------------------
    | Skenario pengujian untuk pendaftaran akun customer baru sesuai dengan 
    | parameter validasi pada AuthController.
    |
    */

    /** TC-005: Customer Registrasi Akun Valid */
    public function test_tc005_customer_registrasi_akun_valid()
    {
        // Mengirimkan request POST ke route /register dengan data lengkap dari TC-005
        $response = $this->post('/register', [
            'username' => 'Isopad23',
            'name' => 'Abdul Hakim',
            'password' => 'Hakim180904',
            'alamat_lengkap' => 'Jl. sana sini No. 16, kota Surabaya',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'provinsi' => 'Jawa Timur',
            'phone' => '085785964677',
            'kota' => 'Surabaya',
        ]);

        // Memastikan data tersimpan di database table 'users'
        $this->assertDatabaseHas('users', [
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'role' => 'customer' // Memastikan AuthController otomatis memberikan role customer
        ]);

        // Menguji apakah response dialihkan kembali ke halaman login
        $response->assertRedirect('/login');
    }

    /** TC-007A: Customer Memasukkan Alamat Email yang sudah digunakan */
    public function test_tc007a_register_email_sudah_digunakan()
    {
        // Membuat user tiruan awal dengan email yang sama
        User::create([
            'name' => 'User Lama', 
            'username' => 'olduser', 
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '08123456789', 
            'password' => bcrypt('password123'), 
            'alamat_lengkap' => 'Surabaya', 
            'provinsi' => 'Jawa Timur', 
            'kota' => 'Surabaya', 
            'role' => 'customer'
        ]);

        // Mencoba mendaftar menggunakan email yang sudah terdaftar di atas
        $response = $this->post('/register', [
            'username' => 'Isopad23',
            'name' => 'Abdul Hakim',
            'password' => 'Hakim180904',
            'alamat_lengkap' => 'Jl. sana sini No. 16, kota Surabaya',
            'email' => 'hakimalbaihaqy100@gmail.com', // Duplikat
            'provinsi' => 'Jawa Timur',
            'phone' => '085785964677',
            'kota' => 'Surabaya',
        ]);

        // Memastikan session memiliki error pada field email
        $response->assertSessionHasErrors('email');
    }

    /** TC-007B: Customer Tidak Memasukkan Data Registrasi (Field Kosong) */
    public function test_tc007b_register_tidak_memasukkan_data_registrasi()
    {
        // Mengirimkan array kosong untuk memicu kegagalan validasi 'required'
        $response = $this->post('/register', []);

        // Memastikan seluruh field wajib dideteksi error oleh Laravel validator
        $response->assertSessionHasErrors([
            'name', 
            'username', 
            'email', 
            'password', 
            'phone', 
            'alamat_lengkap', 
            'provinsi', 
            'kota'
        ]);
    }

    /** BARU - TC-007C: Customer Mengisi Data Registrasi dan input password kurang dari 8 karakter */
    public function test_tc007c_register_password_kurang_dari_8_karakter()
    {
        // Mengirimkan request POST dengan data valid, namun password hanya 7 karakter ('Hakim12')
        $response = $this->post('/register', [
            'username' => 'Revenge',
            'name' => 'Abdul Hakim',
            'password' => 'Hakim12', // Hanya 7 karakter (Memicu aturan 'min:8' di Laravel)
            'alamat_lengkap' => 'Jl. sana sini No. 16, kota Surabaya',
            'email' => 'hakimalbaihaqy@gmail.com',
            'provinsi' => 'Jawa Timur',
            'phone' => '085785964677',
            'kota' => 'Surabaya',
        ]);

        // Memastikan session mendeteksi error validasi khusus pada field 'password'
        $response->assertSessionHasErrors('password');
    }
}