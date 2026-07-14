<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tambahkan status 'draft' dan 'pending_cash' ke enum orders.
 *
 * draft        = order kosong meja berikutnya, belum tampil di cashier
 * pending_cash = order tunai masuk cashier, kasir harus konfirmasi terima uang
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'draft',
            'pending_cash',
            'menunggu',
            'diproses',
            'dibatalkan',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'draft')->update(['status' => 'menunggu']);
        DB::table('orders')->where('status', 'pending_cash')->update(['status' => 'menunggu']);
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'menunggu','diproses','dibatalkan','selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};
