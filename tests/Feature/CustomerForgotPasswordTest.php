<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerForgotPasswordTest extends TestCase
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
    | FITUR: CUSTOMER FORGOT PASSWORD (RESET PASSWORD)
    |--------------------------------------------------------------------------
    | Skenario pengujian untuk pembaruan kata sandi customer berdasarkan
    | parameter validasi dan aturan logika pada AuthController.
    |
    |--------------------------------------------------------------------------
    */

    /** TC-008-A: Customer Mengubah Password dengan Email Valid */
    public function test_tc008a_customer_mengubah_password_dengan_email_valid()
    {
        // Membuat customer master di database dengan password lama
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Hakim180904'), // Password lama
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mengirimkan request POST dengan password baru yang berbeda dan konfirmasi cocok
        $response = $this->post('/forgot-password', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Surabaya2026!',
            'password_confirmation' => 'Surabaya2026!'
        ]);

        // Memastikan diarahkan kembali ke halaman login setelah sukses
        $response->assertRedirect('/login');

        // Memastikan session memiliki flash alert success
        $response->assertSessionHas('success');

        // Mengambil data user terbaru dari database untuk memastikan password berubah
        $user = User::where('email', 'hakimalbaihaqy100@gmail.com')->first();
        $this->assertTrue(Hash::check('Surabaya2026!', $user->password));
    }

    /** TC-008-B: Customer Mengubah Password dengan Email Tidak Terdaftar */
    public function test_tc008b_customer_mengubah_password_dengan_email_tidak_terdaftar()
    {
        // Mengirimkan request dengan email yang salah / tidak terdaftar di database
        $response = $this->post('/forgot-password', [
            'email' => 'palsu@gmail.com',
            'password' => 'Surabaya2026!',
            'password_confirmation' => 'Surabaya2026!'
        ]);

        // Memastikan terjadi error validasi khusus pada field email
        $response->assertSessionHasErrors('email');
    }

    /** TC-008-C: Customer Mengubah Password dengan Konfirmasi Tidak Sesuai */
    public function test_tc008c_customer_mengubah_password_dengan_konfirmasi_tidak_sesuai()
    {
        // Membuat customer master di database
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

        // Mengirimkan request dengan password_confirmation yang tidak sama
        $response = $this->post('/forgot-password', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Surabaya2026!',
            'password_confirmation' => 'PasswordBeda123' // Tidak cocok
        ]);

        // Memastikan terjadi error validasi karena konfirmasi password gagal
        $response->assertSessionHasErrors('password');
    }

    /** TC-008-D: Customer Mengubah Password Menggunakan Password yang Sudah Terdaftar (Sama dengan Password Lama) */
    public function test_tc008d_customer_mengubah_password_menggunakan_password_yang_sudah_terdaftar()
    {
        // Membuat customer master di database
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Madrid-Bayern_1-2'), // Password terdaftar saat ini
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mengirimkan request dengan password baru yang persis sama dengan password lama
        $response = $this->post('/forgot-password', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Madrid-Bayern_1-2', // Menggunakan password lama kembali
            'password_confirmation' => 'Madrid-Bayern_1-2'
        ]);

        // Memastikan ditolak oleh aturan custom penolak password lama di AuthController
        $response->assertSessionHasErrors('password');
        
        // Memastikan password di database tetap bernilai aman (tidak berubah)
        $user = User::where('email', 'hakimalbaihaqy100@gmail.com')->first();
        $this->assertTrue(Hash::check('Madrid-Bayern_1-2', $user->password));
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAHAN SKENARIO BERDASARKAN DOKUMEN EXCEL
    |--------------------------------------------------------------------------
    */

    /** TC-010-D: Customer Mengubah Password dengan Kata Sandi Kurang dari 8 Karakter */
    public function test_tc008e_customer_mengubah_password_dengan_kata_sandi_kurang_dari_8_karakter()
    {
        // Membuat customer master di database
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Hakim180904'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mengirimkan password yang tidak memenuhi syarat panjang minimal 8 karakter (hanya 5 karakter)
        $response = $this->post('/forgot-password', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Abdul',
            'password_confirmation' => 'Abdul'
        ]);

        // Memastikan sistem memicu error validasi pada field password akibat batasan minimal karakter
        $response->assertSessionHasErrors('password');
    }

    /** TC-10-E: Customer Mengubah Password dengan Mengosongkan Kolom Konfirmasi Kata Sandi */
    public function test_tc008f_customer_mengubah_password_dengan_konfirmasi_kata_sandi_kosong()
    {
        // Membuat customer master di database
        User::create([
            'name' => 'Abdul Hakim',
            'username' => 'Isopad23',
            'email' => 'hakimalbaihaqy100@gmail.com',
            'phone' => '085785964677',
            'password' => bcrypt('Hakim180904'),
            'alamat_lengkap' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'role' => 'customer'
        ]);

        // Mengirimkan request dengan membiarkan string konfirmasi kosong (tidak diisi)
        $response = $this->post('/forgot-password', [
            'email' => 'hakimalbaihaqy100@gmail.com',
            'password' => 'Abdul180904',
            'password_confirmation' => '' // Field dikosongkan sesuai kasus uji TC-10-E
        ]);

        // Memastikan sistem menolak dan mengembalikan error validasi field required / confirmation
        $response->assertSessionHasErrors('password');
    }
}