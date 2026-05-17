<?php

namespace App\Http\Controllers;

// Baris wajib agar sub-folder mengenali Controller bawaan Laravel
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
    // Menampilkan halaman isi keranjang belanja pelanggan
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        return view('cart/index', compact('cart'));
    }

    // Menambahkan produk pilihan ke dalam sistem keranjang belanja
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Ambil data keranjang saat ini atau buat baru jika belum memiliki wadah keranjang
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Periksa keberadaan produk serupa di dalam item keranjang
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Akumulasikan kuantitas belanja jika produk sudah ada sebelumnya
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok komoditas produk tidak mencukupi batas maksimal.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Daftarkan item baru ke tabel cart_items
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dimasukkan ke keranjang belanja.');
    }

    // Memperbarui kuantitas produk dari form halaman internal keranjang
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Jumlah belanjaan berhasil diperbarui.');
    }

    // Menghapus baris produk tertentu dari daftar belanjaan keranjang
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk berhasil dikeluarkan dari keranjang.');
    }

    // Konversi isi komoditas keranjang menjadi struktur data pesanan resmi (Order)
    public function checkout()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda saat ini masih kosong.');
        }

        // Menggunakan Database Transaction demi keamanan integritas relasi antar-tabel database
        DB::beginTransaction();

        try {
            $totalPrice = 0;

            // Tahap 1: Validasi ulang seluruh ketersediaan stok produk
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->route('cart.index')->with('error', "Stok barang produk {$item->product->name} tidak mencukupi untuk diproses.");
                }
                $totalPrice += $item->quantity * $item->product->price;
            }

            // Tahap 2: Menyimpan data ke tabel utama `orders` sesuai spesifikasi SQL Anda
            $order = Order::create([
                'user_id'        => Auth::id(),
                'code'           => 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'total_price'    => $totalPrice,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Tahap 3: Pemindahan massal data item menuju tabel `order_items` beserta pengurangan stok
            foreach ($cart->items as $item) {
                $subtotal = $item->quantity * $item->product->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                    'subtotal'   => $subtotal,
                ]);

                // Kurangi stok riil di gudang data produk
                $item->product->decrement('stock', $item->quantity);
            }

            // Tahap 4: Membersihkan instansiasi data keranjang yang telah selesai dipesan
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            // Dilemparkan ke halaman detail order bawaan proyek Anda agar alur Midtrans bekerja otomatis
            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil diterbitkan! Silakan segera lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}