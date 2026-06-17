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
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Gunakan transaksi database agar data sinkron
        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $product->id)
                                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                
                // Pastikan sisa stok mencukupi untuk tambahan ini
                if ($request->quantity > $product->stock) {
                    return redirect()->back()->with('error', 'Stok produk tidak mencukupi untuk tambahan ini.');
                }
                
                CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id'    => $cart->id,
                    'user_id'    => Auth::id(),
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                    'price'      => $product->price,
                ]);
            }

            // PERBAIKAN: Kurangi stok produk secara langsung
            $product->decrement('stock', $request->quantity);

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

        $product = $cartItem->product;
        $oldQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity;

        // Cari selisih quantity (apakah user menambah atau mengurangi jumlah di keranjang)
        $difference = $newQuantity - $oldQuantity;

        // Jika user menambah qty, cek apakah stok produk utamanya masih cukup
        if ($difference > 0 && $difference > $product->stock) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk penambahan.');
        }

        DB::beginTransaction();
        try {
            CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $newQuantity]);

            
            if ($difference > 0) {
                // Jika keranjang ditambah, stok produk utama dikurangi
                $product->decrement('stock', $difference);
            } elseif ($difference < 0) {
                // Jika keranjang dikurangi, stok produk utama dikembalikan
                $product->increment('stock', abs($difference));
            }

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

        $product = Product::findOrFail($productId);

        DB::beginTransaction();
        try {
            // PERBAIKAN: Kembalikan stok produk ke database karena batal dibeli (dihapus dari keranjang)
            $product->increment('stock', $cartItem->quantity);

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
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda saat ini masih kosong.');
        }

        DB::beginTransaction();

        try {
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
                $subtotal = $item->quantity * $item->product->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                    'subtotal'   => $subtotal,
                ]);

                // PERBAIKAN PENTING:
                // Baris $item->product->decrement('stock', $item->quantity); 
                // DIHAPUS dari sini, karena stok sudah dipotong waktu klik 'add to cart'.
            }

            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil diterbitkan! Silakan segera lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}