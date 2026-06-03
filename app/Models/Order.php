<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // 1. Relasi ke tabel order_items
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    // 2. Relasi ke tabel payments (opsional tapi penting untuk halaman detail)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    // 3. Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}