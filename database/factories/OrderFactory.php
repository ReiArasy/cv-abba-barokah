<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Nama model yang direpresentasikan oleh factory.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Jika user_id tidak didefinisikan di test, otomatis akan membuat User baru
            'user_id' => User::factory(),
            
            // Generate kode unik, contoh: INV-A1B2C3D4
            'code' => 'INV-' . strtoupper(Str::random(8)),
            
            // Harga acak antara Rp 50.000 sampai Rp 2.000.000
            'total_price' => $this->faker->numberBetween(50000, 2000000),
            
            // Status pembayaran acak
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid', 'failed']),
            
            // Status pengiriman/pesanan acak
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
        ];
    }
}