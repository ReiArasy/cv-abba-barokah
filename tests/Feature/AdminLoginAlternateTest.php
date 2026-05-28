<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Auth\Login;

class AdminLoginAlternateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // SETUP: Membuat admin user hardcoded
        User::create([
            'name' => 'Abba Admin',
            'email' => 'abba@gmail.com',
            'password' => bcrypt('abba123'),
            'role' => 'admin',
        ]);
    }

    /**
     * TC-001A1: test_admin_cannot_login_with_wrong_password
     */
    public function test_admin_cannot_login_with_wrong_password()
    {
        // SETUP
        $email = 'abba@gmail.com';
        $password = 'abba1234'; // Salah

        // ACTION: Simulasikan submit form login dengan password salah
        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
            ])
            ->call('authenticate');

        // ASSERTION: Mengharapkan error pada form dan user tetap guest
        $component->assertHasFormErrors(['email']);
        $this->assertGuest();
    }

    /**
     * TC-001A2a: test_admin_cannot_login_with_empty_email
     */
    public function test_admin_cannot_login_with_empty_email()
    {
        // SETUP
        $email = ''; // Kosong
        $password = 'abba123';

        // ACTION: Simulasikan submit form login dengan email kosong
        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
            ])
            ->call('authenticate');

        // ASSERTION: Mengharapkan error validasi email dan user tetap guest
        $component->assertHasFormErrors(['email']);
        $this->assertGuest();
    }

    /**
     * TC-001A2b: test_admin_cannot_login_with_empty_password
     */
    public function test_admin_cannot_login_with_empty_password()
    {
        // SETUP
        $email = 'abba@gmail.com';
        $password = ''; // Kosong

        // ACTION: Simulasikan submit form login dengan password kosong
        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
            ])
            ->call('authenticate');

        // ASSERTION: Mengharapkan error validasi password dan user tetap guest
        $component->assertHasFormErrors(['password']);
        $this->assertGuest();
    }

    /**
     * TC-001A3: test_admin_cannot_login_with_invalid_email_format
     */
    public function test_admin_cannot_login_with_invalid_email_format()
    {
        // SETUP
        $email = 'abba.gmail.com'; // Tanpa @
        $password = 'abba123';

        // ACTION: Simulasikan submit form login dengan format email tidak valid
        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
            ])
            ->call('authenticate');

        // ASSERTION: Mengharapkan error validasi email dan user tetap guest
        $component->assertHasFormErrors(['email']);
        $this->assertGuest();
    }

    /**
     * TC-001A4: test_admin_cannot_login_with_email_containing_space
     */
    public function test_admin_cannot_login_with_email_containing_space()
    {
        // SETUP
        $email = 'abba @gmail.com'; // Ada spasi
        $password = 'abba123';

        // ACTION: Simulasikan submit form login dengan email mengandung spasi
        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
            ])
            ->call('authenticate');

        // ASSERTION: Mengharapkan error validasi email dan user tetap guest
        $component->assertHasFormErrors(['email']);
        $this->assertGuest();
    }
}
