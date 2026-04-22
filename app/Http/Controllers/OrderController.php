<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order; 
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock tidak cukup');
        }

        DB::transaction(function () use ($request, $product) {

            $total = $product->price * $request->quantity;

            Order::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $total,
            ]);

            $product->decrement('stock', $request->quantity);
        });

        return redirect()->route('orders.history');
    }
}
