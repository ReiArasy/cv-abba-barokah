<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Resources\CategoryResource;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // SETUP: Membuat admin user hardcoded
        $this->admin = User::create([
            'name' => 'Abba Admin',
            'email' => 'abba@gmail.com',
            'password' => bcrypt('abba123'),
            'role' => 'admin',
        ]);
    }

    /**
     * TC-004A1: test_admin_cannot_create_category_with_empty_name
     */
    public function test_admin_cannot_create_category_with_empty_name()
    {
        // SETUP: Login sebagai admin
        $admin = User::where('email', 'abba@gmail.com')->first();
        $this->actingAs($admin);

        // ACTION: Mencoba membuat kategori dengan nama kosong
        $component = Livewire::test(CategoryResource\Pages\CreateCategory::class)
            ->fillForm([
                'name' => '',
                'is_active' => true,
            ])
            ->call('create');

        // ASSERTION: Cek validation error untuk 'name' dan kategori tidak tersimpan di database
        $component->assertHasFormErrors(['name']);
        $this->assertDatabaseMissing('categories', [
            'name' => '',
        ]);
        $this->assertDatabaseCount('categories', 0);
    }
}
