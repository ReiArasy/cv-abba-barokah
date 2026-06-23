<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerLoginTest extends TestCase
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
    | FITUR: CUSTOMER LOGIN
    |--------------------------------------------------------------------------
    | Skenario pengujian untuk autentikasi masuk akun customer berdasarkan
    | parameter dan rules pada AuthController.
    |
    */

    /** TC-006: Customer Login Akun Valid */
    public function test_tc006_customer_login_akun_valid()
    {
        // Membuat data customer master di database menggunakan password yang di-bcrypt
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Abdul180904'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mengirimkan request POST login dengan data yang sesuai
        $response = $this->post('/login', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Abdul180904'
        ]);

        // Memastikan customer berhasil login (authenticated)
        $this->assertAuthenticated();

        // Memastikan sistem mengarahkan customer ke landing page utama (route 'home' / '/')
        $response->assertRedirect('/');
    }

    /** TC-006A: Customer Login Akun Invalid (Password Salah) */
    public function test_tc006a_customer_login_akun_invalid()
    {
        // Membuat data customer master di database
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Madrid-Bayern_1-2'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mencoba login dengan password yang salah ('salah123')
        $response = $this->post('/login', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'salah123'
        ]);

        // Memastikan customer tetap tidak terautentikasi (guest)
        $this->assertGuest();

        // Memastikan session melempar error validation pada field email akibat kredensial salah
        $response->assertSessionHasErrors('email');
    }

    /** TC-006B: Customer Login Field Kosong */
    public function test_tc006b_customer_login_field_kosong()
    {
        // Mengirimkan data kosong ke route login
        $response = $this->post('/login', [
            'email' => '',
            'password' => ''
        ]);

        // Memastikan customer tidak masuk sistem
        $this->assertGuest();

        // Memastikan validator Laravel menangkap error 'required' dari email dan password
        $response->assertSessionHasErrors(['email', 'password']);
    }
}