<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderDetails.menu', 'payments'])
            ->whereIn('status', ['selesai', 'dibatalkan']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('table_id', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal Awal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter Tanggal Akhir
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter Metode Pembayaran
        if ($request->filled('payment')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('payment_method', $request->payment);
            });
        }

        // Hitung ringkasan dari SELURUH hasil filter, bukan cuma 1 halaman
        $totalCount      = (clone $query)->count();
        $selesaiCount    = (clone $query)->where('status', 'selesai')->count();
        $dibatalkanCount = (clone $query)->where('status', 'dibatalkan')->count();

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.history.History', compact(
            'orders',
            'totalCount',
            'selesaiCount',
            'dibatalkanCount'
        ));
    }
}