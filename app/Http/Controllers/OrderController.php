<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $cart = $user->cart ?? $user->cart()->create();
    $cart->load('items.product');

    if ($cart->items->isEmpty()) {
        return back()->with('error', 'Keranjang kosong');
    }

    DB::transaction(function () use ($cart, $user) {

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 0,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $total = 0;

        foreach ($cart->items as $item) {

            if ($item->product->stock < $item->quantity) {
                throw new \Exception('Stock tidak cukup untuk ' . $item->product->name);
            }

            $subtotal = $item->price * $item->quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $subtotal,
            ]);

            $item->product->decrement('stock', $item->quantity);

            $total += $subtotal;
        }

        $order->update([
            'total_price' => $total
        ]);

        $cart->items()->delete();
    });

    return redirect()->route('orders.history');
   }
}
