<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Case 1: Customer dapat melihat history pembelian
     * Expected Result: Sistem menampilkan history pembelian
     */
    public function test_customer_can_view_purchase_history()
    {
        // Arrange: Buat user dan pesanan (order) untuk user tersebut
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'code' => 'INV-12345',
            'total_price' => 250000,
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        // Act: Kunjungi halaman riwayat pembelian sebagai user yang sudah login
        $response = $this->actingAs($user)->get(route('purchase.history'));

        // Assert: Pastikan status 200 (OK) dan data pesanan tampil di layar
        $response->assertStatus(200);
        $response->assertViewIs('purchase.history');
        $response->assertSee('INV-12345');
        $response->assertSee('250.000'); // Memastikan format harga muncul
    }

    /**
     * Test Case 2: Customer dapat melihat detail history pembelian
     * Expected Result: Sistem Berhasil Menampilkan detail history pembelian
     */
    public function test_customer_can_view_purchase_history_detail()
    {
        // Arrange: Buat user dan pesanan
        $user = User::factory()->create();
        
        // PENTING: Set payment_status ke 'paid' agar test tidak memicu
        // pemanggilan API eksternal Midtrans (\Midtrans\Snap::getSnapToken) 
        // yang dapat menyebabkan error koneksi saat testing.
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'code' => 'INV-67890',
            'payment_status' => 'paid' 
        ]);

        // Act: Kunjungi halaman detail pesanan
        $response = $this->actingAs($user)->get(route('purchase.show', $order->code));

        // Assert: Pastikan status 200 (OK) dan menggunakan view yang tepat
        $response->assertStatus(200);
        $response->assertViewIs('purchase.show');
        $response->assertViewHas('order', function ($viewOrder) use ($order) {
            return $viewOrder->id === $order->id;
        });
    }

    /**
     * Test Case 3: History pembelian akan kosong jika customer belum membeli produk
     * Expected Result: Sistem menampilkan belum ada riwayat pembelian
     */
    public function test_system_shows_empty_message_if_no_purchase_history()
    {
        // Arrange: Buat user BARU yang tidak memiliki data pesanan sama sekali
        $user = User::factory()->create();

        // Act: Kunjungi halaman riwayat pembelian
        $response = $this->actingAs($user)->get(route('purchase.history'));

        // Assert: Pastikan status 200 dan teks fallback dari view muncul
        $response->assertStatus(200);
        $response->assertSee('Belum ada riwayat pembelian saat ini.');
    }
    
    /**
     * Test Case Tambahan: Keamanan (Authorization)
     * Expected Result: Customer tidak bisa melihat detail pesanan milik orang lain
     */
    public function test_customer_cannot_view_other_users_purchase_detail()
    {
        // Arrange: Buat 2 user berbeda
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        // Buat pesanan milik User 1
        $order = Order::factory()->create([
            'user_id' => $user1->id,
            'code' => 'INV-SECRET',
        ]);

        // Act: User 2 mencoba mengakses detail pesanan milik User 1
        $response = $this->actingAs($user2)->get(route('purchase.show', $order->code));

        // Assert: Pastikan sistem menolak dengan status 403 (Forbidden)
        $response->assertStatus(403);
        $response->assertSee('Akses ditolak.');
    }
}