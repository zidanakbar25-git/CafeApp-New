<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Manager diarahkan ke halaman manajemen meja sebagai "home" mereka
        // (karena dashboard pesanan adalah domain kasir)
        if ($user->role === 'manager') {
            return redirect()->route('admin.tables.index');
        }

        // ── Kasir / role lain: tampilkan dashboard pesanan ──
        $tab = $request->get('tab', 'aktif');

        $query = Order::with(['orderDetails.menu', 'payments'])->latest();

        if ($tab === 'aktif') {
            $query->whereIn('status', ['pending_cash', 'menunggu', 'diproses'])
                  ->whereNotNull('payment_method')
                  ->whereNotNull('paid_at');
        } elseif ($tab === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($tab === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        $countAktif = Order::whereIn('status', ['pending_cash', 'menunggu', 'diproses'])
                           ->whereNotNull('payment_method')
                           ->whereNotNull('paid_at')
                           ->count();

        $countSelesai    = Order::where('status', 'selesai')->count();
        $countDibatalkan = Order::where('status', 'dibatalkan')->count();

        return view('admin.cashier.dashboard.index', compact(
            'orders', 'tab',
            'countAktif', 'countSelesai', 'countDibatalkan'
        ));
    }
}