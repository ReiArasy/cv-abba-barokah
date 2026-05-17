<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];

    // Relasi balik ke induk keranjang belanja
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    // Relasi mengambil informasi data produk pendukung
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}