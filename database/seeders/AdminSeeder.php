<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- Pastikan Model User di-import

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Jika dijalankan ulang, tidak akan duplikat
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'phone' => '081234567890',
                'alamat_lengkap' => 'Kantor Pusat',
                'provinsi' => 'Jawa Timur',
                'kota' => 'Surabaya',
                'role' => 'admin', // <-- Mengunci role sebagai admin
                'password' => bcrypt('admin123'), // <-- Password login admin Anda
            ]
        );
    }
}