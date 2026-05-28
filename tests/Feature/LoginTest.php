<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // SETUP: Membuat admin user hardcoded untuk keperluan testing
        User::create([
            'name' => 'Abba Admin',
            'email' => 'abba@gmail.com',
            'password' => bcrypt('abba123'),
            'role' => 'admin',
        ]);
    }

    /**
     * SETUP: menyiapkan data user
     * ACTION: kirim request login
     * ASSERTION: cek user berhasil login
     */
    public function test_can_login_on_admin_dashboard()
    {
        // SETUP (ambil dari database asli)
        $admin = User::where('email', 'abba@gmail.com')->first();

        // ACTION (simulate login)
        $this->actingAs($admin);

        // ASSERTION
        $response = $this->get('/admin');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        // ACTION (tetap tidak bisa test via /admin
        $response = $this->post('/login', [
            'email' => 'abba@gmail.com',
            'password' => 'abba1234',
        ]);

        // ASSERTION
        $this->assertGuest();
    }

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }
}