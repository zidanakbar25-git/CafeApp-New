<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index($order_id)
    {
        $data = $this->paymentService->getCheckoutData($order_id);
        return view('customer.payment.index', $data);
    }

    public function cash($id)
    {
        $order = Order::with('orderDetails.menu')->findOrFail($id);
        $total = $order->orderDetails->sum('subtotal');

        if (!in_array($order->status, ['draft', 'pending_cash'])) {
        return redirect()->route('payment.success', $id);
    }

        if ($order->status === 'draft') {
            $order->status         = 'pending_cash';
            $order->payment_method = 'cash';
            $order->paid_at        = now(); // ← tambah ini
            $order->save();

            Payment::create([
                'order_id'       => $order->order_id,
                'admin_id'       => null,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'paid_at'        => null,
            ]);
        }
        return view('customer.payment.cash.cash', compact('order', 'total'));
    }

    public function qris($order_id)
    {
        $data = $this->paymentService->getCheckoutData($order_id);
        return view('customer.payment.Qris.index', $data);
    }

    public function cc($order_id)
    {
        $data = $this->paymentService->getCheckoutData($order_id);
        return view('customer.payment.creditcard.index', $data);
    }

    public function process(Request $request, $order_id)
    {
        $request->validate([
            'customer_name' => 'required'
        ]);

        $order = Order::findOrFail($order_id);
        $order->customer_name = $request->customer_name;
        $order->save();

        if ($request->payment_method === 'qris') {
            return redirect()->route('payment.qris', $order_id);
        }
        if ($request->payment_method === 'cash') {
            return redirect()->route('payment.cash.show', $order_id);
        }
        if ($request->payment_method === 'cc') {
            return redirect()->route('payment.cc', $order_id);
        }

        return back();
    }

    public function success($order_id)
    {
        $order = Order::with(['orderDetails.menu', 'payments'])->findOrFail($order_id);
        $items = $order->orderDetails;
        $total = $order->total_amount;

        return view('customer.ordersuccess.index', compact('order', 'items', 'total'));
    }

    /**
     * Finalize pembayaran:
     * - Cash       → status 'pending_cash' (kasir konfirmasi terima uang dulu)
     * - Non-cash   → status 'menunggu'     (langsung antrian kasir)
     * - Hapus semua draft lama untuk meja ini sebelum buat draft baru
     * - Buat 1 draft bersih untuk meja berikutnya
     */
    public function finalizePayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $isCash = $request->payment_method === 'cash';

        $order->status         = $isCash ? 'pending_cash' : 'menunggu';
        $order->payment_method = $request->payment_method;
        $order->paid_at        = now();
        $order->save();

        // Buat record di tabel payments
        Payment::create([
            'order_id'       => $order->order_id,
            'admin_id'       => null,
            'payment_method' => $request->payment_method,
            'payment_status' => $isCash ? 'pending' : 'paid',
            'paid_at'        => $isCash ? null : now(),
        ]);

        // Hapus semua draft lama untuk meja ini agar tidak menumpuk
        $oldDrafts = Order::where('table_id', $order->table_id)
            ->where('status', 'draft')
            ->get();

        foreach ($oldDrafts as $draft) {
            $draft->orderDetails()->delete();
            $draft->delete();
        }

        // Buat 1 draft bersih untuk customer berikutnya
        Order::create([
            'table_id'     => $order->table_id,
            'order_code'   => 'ORD-' . strtoupper(Str::random(6)),
            'status'       => 'draft',
            'total_amount' => 0,
        ]);

        return redirect()->route('payment.success', $order_id);
    }
}
