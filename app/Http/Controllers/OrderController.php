<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Alur Status:
     *   draft        → (tidak bisa diubah manual, hanya internal)
     *   pending_cash → menunggu   (kasir konfirmasi terima uang tunai)
     *   pending_cash → dibatalkan
     *   menunggu     → diproses → selesai
     *   menunggu     → dibatalkan
     *   diproses     → dibatalkan
     *   selesai      → menunggu  (kembalikan)
     *   dibatalkan   → menunggu  (kembalikan)
     */
    private array $allowedTransitions = [
        'draft'        => [],
        'pending_cash' => ['menunggu', 'dibatalkan'],
        'menunggu'     => ['diproses', 'dibatalkan'],
        'diproses'     => ['selesai',  'dibatalkan'],
        'selesai'      => ['menunggu'],
        'dibatalkan'   => ['menunggu'],
    ];

    /**
     * Update status pesanan (dipanggil via AJAX)
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:draft,pending_cash,menunggu,diproses,selesai,dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $newStatus = $request->status;

        $allowed = $this->allowedTransitions[$order->status] ?? [];
        if (!in_array($newStatus, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => "Tidak bisa mengubah status dari '{$order->status}' ke '{$newStatus}'.",
            ], 422);
        }

        // Konfirmasi tunai: tandai payment sebagai paid
        if ($order->status === 'pending_cash' && $newStatus === 'menunggu') {
            Payment::where('order_id', $order->order_id)
                   ->where('payment_status', 'pending')
                   ->update([
                       'payment_status' => 'paid',
                       'paid_at'        => now(),
                       'admin_id'       => auth()->id(),
                   ]);
        }

        if ($newStatus === 'selesai') {
            $order->completed_at = now();
        }

        if ($newStatus === 'menunggu') {
            $order->completed_at = null;
        }

        $order->status = $newStatus;
        $order->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Status pesanan berhasil diubah.',
            'new_status' => $newStatus,
            'order_id'   => $order->order_id,
        ]);
    }

    /**
     * Hapus pesanan — cascade hapus payments & order_details dulu
     */
    public function destroy(int $id)
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->status, ['selesai', 'dibatalkan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan selesai atau dibatalkan yang bisa dihapus.',
            ], 422);
        }

        DB::transaction(function () use ($order) {
            Payment::where('order_id', $order->order_id)->delete();
            $order->orderDetails()->delete();
            $order->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus.',
        ]);
    }

    /**
     * Struk: tampilkan halaman cetak struk
     */
    public function struk(int $id)
    {
        $order = Order::with(['orderDetails.menu', 'payments'])->findOrFail($id);

        $payment = $order->payments()->latest()->first();
        $paymentMethod = $payment?->payment_method ?? $order->payment_method ?? '-';
        $kasir = auth()->user()->username; 

        return view('admin.cashier.dashboard.struk', compact('order', 'payment', 'paymentMethod', 'kasir'));
    }
}
