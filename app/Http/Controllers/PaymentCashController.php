<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentCashController extends Controller
{
    /**
     * Tampilkan nota pembayaran cash.
     * GET /payment/cash/{order_id}
     */
    public function show($order_id)
    {
        $order = Order::with('orderDetails.menu')->findOrFail($order_id);
        $total = $order->orderDetails->sum('subtotal');

        return view('customer.payment.cash.cash', compact('order', 'total'));
    }
}