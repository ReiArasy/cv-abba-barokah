<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function initMidtrans()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

   
    public function checkout(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();

        try {

            $totalPrice = 0;

            foreach ($cart->items as $item) {

                if ($item->product->stock < $item->quantity) {
                    throw new \Exception(
                        'Stok produk ' . $item->product->name . ' tidak mencukupi.'
                    );
                }

                $totalPrice += ($item->quantity * $item->product->price);
            }

            do {
                $orderCode = 'ORD-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
            } while (Order::where('code', $orderCode)->exists());

            $order = Order::create([
                'user_id' => $user->id,
                'code' => $orderCode,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->quantity * $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            DB::commit();

            return redirect()
                ->route('orders.show', ['order' => $order->code])
                ->with('success', 'Pesanan berhasil dibuat, silakan lakukan pembayaran.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }
    }

   
    public function directCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with(
                'error',
                'Stok produk tidak mencukupi.'
            );
        }

        DB::beginTransaction();

        try {

            $totalPrice = $product->price * $request->quantity;

            do {
                $orderCode = 'ORD-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
            } while (Order::where('code', $orderCode)->exists());

            $order = Order::create([
                'user_id' => $user->id,
                'code' => $orderCode,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $totalPrice,
            ]);

            $product->decrement('stock', $request->quantity);

            DB::commit();

            return redirect()
                ->route('orders.show', ['order' => $order->code])
                ->with('success', 'Pesanan berhasil dibuat, silakan lakukan pembayaran.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.history', compact('orders'));
    }
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $order->load('items.product');

        $snapToken = null;

        if ($order->payment_status === 'unpaid') {

            $this->initMidtrans();

           
            if (!empty($order->snap_token)) {

                $snapToken = $order->snap_token;

            } else {

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->code,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $order->update([
                    'snap_token' => $snapToken
                ]);
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }

     //Callback Midtrans
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $hashed = hash(
            "sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed == $request->signature_key) {

            $order = Order::where(
                'code',
                $request->order_id
            )->first();

            if ($order) {

                if (
                    $request->transaction_status == 'capture' ||
                    $request->transaction_status == 'settlement'
                ) {

                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid'
                    ]);

                    Payment::updateOrCreate(
                        [
                            'order_id' => $order->id
                        ],
                        [
                            'payment_method' => $request->payment_type,
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'raw_response' => json_encode($request->all())
                        ]
                    );

                } elseif (
                    in_array(
                        $request->transaction_status,
                        ['cancel', 'deny', 'expire']
                    )
                ) {

                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'failed',
                        'snap_token' => null
                    ]);

                    Payment::updateOrCreate(
                        [
                            'order_id' => $order->id
                        ],
                        [
                            'payment_method' => $request->payment_type,
                            'payment_status' => 'failed',
                            'raw_response' => json_encode($request->all())
                        ]
                    );
                }
            }
        }

        return response()->json([
            'message' => 'Sukses'
        ]);
    }
}