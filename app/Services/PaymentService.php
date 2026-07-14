<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    public function getCheckoutData(int $orderId): array
    {
        $order = Order::with('orderDetails.menu')->findOrFail($orderId);

        $subtotal = $order->orderDetails->sum('subtotal');

        $total = $subtotal;

        return [
            'order' => $order,
            'items' => $order->orderDetails,
            'subtotal' => $subtotal,
            'total' => $total,
        ];
    }
}