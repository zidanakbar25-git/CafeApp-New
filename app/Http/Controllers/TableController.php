<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Halaman daftar semua meja & QR Code
     */
    public function index(Request $request)
    {
        $query = CafeTable::orderByRaw('CAST(table_number AS UNSIGNED)');

        if ($request->filled('search')) {
            $query->where('table_number', 'like', '%' . $request->search . '%');
        }

        $tables = $query->get();

        return view('admin.manager.management.tables.index', compact('tables'));
    }

    /**
     * Tambah meja baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|min:1|unique:cafe_tables,table_number',
        ]);

        CafeTable::create([
            'table_number' => $request->table_number,
            'qr_token'     => Str::uuid(),
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja ' . $request->table_number . ' berhasil ditambahkan.');
    }

    /**
     * Hapus semua riwayat order (kecuali draft aktif) untuk meja ini
     */
    public function clearHistory(CafeTable $table)
    {
        $orders = \App\Models\Order::where('table_id', $table->table_id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->get();

        foreach ($orders as $order) {
            \App\Models\Payment::where('order_id', $order->order_id)->delete();
            $order->orderDetails()->delete();
            $order->delete();
        }

        return redirect()->route('admin.tables.index')
            ->with('success', 'Riwayat order meja ' . $table->table_number . ' berhasil dibersihkan.');
    }

    /**
     * Hapus meja 
     */
    public function destroy(CafeTable $table)
    {
        // Blokir kalau ada order aktif
        $hasActiveOrders = \App\Models\Order::where('table_id', $table->table_id)
            ->whereNotIn('status', ['draft', 'selesai', 'dibatalkan'])
            ->exists();

        if ($hasActiveOrders) {
            return redirect()->route('admin.tables.index')
                ->with('error', 'Meja ' . $table->table_number . ' tidak bisa dihapus karena masih ada pesanan aktif.');
        }

        // Hapus draft
        \App\Models\Order::where('table_id', $table->table_id)
            ->where('status', 'draft')
            ->each(function ($order) {
                $order->orderDetails()->delete();
                $order->delete();
            });

        // Order selesai/dibatalkan → table_id otomatis jadi null (onDelete set null)
        $nomor = $table->table_number;
        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja ' . $nomor . ' berhasil dihapus.');
    }

    /**
     * Halaman cetak QR Code satu meja
     */
    public function print(CafeTable $table)
    {
        return view('admin.manager.management.tables.print', compact('table'));
    }


    
}
