<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    // Relasi satu keranjang memiliki banyak item di dalamnya
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    // Relasi balik ke entitas Pengguna pemilik keranjang
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}