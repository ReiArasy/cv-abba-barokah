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

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok komoditas produk tidak mencukupi batas maksimal.');
            }
            
            // PERBAIKAN: Gunakan Query Builder langsung agar tidak mencari kolom 'id'
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

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dimasukkan ke keranjang belanja.');
    }

    // PERBAIKAN: Parameter diubah menjadi $productId karena kita tidak punya $id di tabel cart_items
    public function update(Request $request, $productId) 
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            abort(403, 'Data keranjang tidak valid.');
        }

        // Cari item berdasarkan cart_id dan product_id
        $cartItem = CartItem::with('product')
                            ->where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        // PERBAIKAN: Update menggunakan Query Builder
        CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Jumlah belanjaan berhasil diperbarui.');
    }

    // PERBAIKAN: Parameter diubah menjadi $productId
    public function remove($productId)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // PERBAIKAN: Hapus menggunakan Query Builder
        CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->delete();

        return redirect()->back()->with('success', 'Produk berhasil dikeluarkan dari keranjang.');
    }

    public function checkout()
    {
        // Kode Checkout tidak perlu diubah, biarkan seperti aslinya
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda saat ini masih kosong.');
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;

            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->route('cart.index')->with('error', "Stok barang produk {$item->product->name} tidak mencukupi untuk diproses.");
                }
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

                $item->product->decrement('stock', $item->quantity);
            }

            // PERBAIKAN: Hapus isi cart menggunakan Query Builder
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