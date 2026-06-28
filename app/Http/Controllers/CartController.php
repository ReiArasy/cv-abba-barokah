<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        return view('cart/index', compact('cart'));
    }
    public function add(Request $request, Product $product)
    {   
        $cart = Cart::where('user_id', Auth::id())->first();
        $quantityInCart = 0;
        if ($cart) {
            $existingItem = CartItem::where('cart_id', $cart->id)
                                    ->where('product_id', $product->id)
                                    ->first();
            $quantityInCart = $existingItem ? $existingItem->quantity : 0;
        }

        // Sisa stok yang benar-benar bisa ditambahkan
        $remainingStock = $product->stock - $quantityInCart;

        // Jika sisa stok sudah 0, langsung tolak
        if ($remainingStock <= 0) {
            return redirect()->back()->with(
                'error',
                "Stok produk \"{$product->name}\" sudah habis atau sudah penuh di keranjang Anda."
            );
        }

        $request->validate([
            'quantity' => "required|integer|min:1|max:{$remainingStock}",
        ], [
            'quantity.required' => 'Jumlah produk wajib diisi.',
            'quantity.integer'  => 'Jumlah produk harus berupa angka.',
            'quantity.min'      => 'Jumlah minimal adalah 1.',
            'quantity.max'      => "Tidak bisa menambahkan {$request->quantity} item. "
                                 . "Stok tersedia: {$product->stock}, sudah di keranjang: {$quantityInCart}, "
                                 . "sisa yang bisa ditambah: {$remainingStock}.",
        ]);

        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $product->id)
                                ->first();

            if ($cartItem) {
                // Produk sudah ada di keranjang → update qty
                CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->update(['quantity' => $cartItem->quantity + $request->quantity]);
            } else {
                // Produk belum ada di keranjang → buat baru
                CartItem::create([
                    'cart_id'    => $cart->id,
                    'user_id'    => Auth::id(),
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                    'price'      => $product->price,
                ]);
            }

            DB::commit();
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dimasukkan ke keranjang belanja.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function update(Request $request, $productId)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            abort(403, 'Data keranjang tidak valid.');
        }

        $cartItem = CartItem::with('product')
                            ->where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->firstOrFail();
        $product     = $cartItem->product;
        $newQuantity = (int) $request->quantity; 
        if ($newQuantity < 1) {
            return redirect()->back()->with(
                'error',
                'Jumlah minimal adalah 1. Gunakan tombol hapus jika ingin mengeluarkan produk.'
            );
        }
        if ($newQuantity > $product->stock) {
            return redirect()->back()->with(
                'error',
                "Jumlah melebihi stok yang tersedia. Stok tersedia: {$product->stock}."
            );
        }

        DB::beginTransaction();
        try {
            CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $newQuantity]);
            DB::commit();
            return redirect()->back()->with('success', 'Jumlah belanjaan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function remove($productId)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->firstOrFail();

        DB::beginTransaction();
        try {
            CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil dikeluarkan dari keranjang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function checkout()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with(
                'error',
                'Keranjang belanja Anda saat ini masih kosong.'
            );
        }

        DB::beginTransaction();
        try {
        
            foreach ($cart->items as $item) {
            
                $product = Product::lockForUpdate()->find($item->product_id);

                if ($item->quantity > $product->stock) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with(
                        'error',
                        "Stok produk \"{$product->name}\" tidak mencukupi. "
                        . "Tersedia: {$product->stock}, di keranjang: {$item->quantity}. "
                        . "Silakan sesuaikan jumlah sebelum checkout."
                    );
                }
            }

            $totalPrice = 0;
            foreach ($cart->items as $item) {
                $totalPrice += $item->quantity * $item->product->price;
            }

            $order = Order::create([
                'user_id'        => Auth::id(),
                'code'           => 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'total_price'    => $totalPrice,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                    'subtotal'   => $item->quantity * $item->product->price,
                ]);

                // Stok baru dikurangi di sini setelah order berhasil dibuat
                $item->product->decrement('stock', $item->quantity);
            }
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();

            DB::commit();
            return redirect()->route('orders.show', $order->id)->with(
                'success',
                'Pesanan berhasil diterbitkan! Silakan segera lakukan pembayaran.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with(
                'error',
                'Gagal memproses transaksi: ' . $e->getMessage()
            );
        }
    }
}