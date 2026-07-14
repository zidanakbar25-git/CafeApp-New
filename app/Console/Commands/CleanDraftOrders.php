<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CleanDraftOrders extends Command
{
    protected $signature   = 'orders:clean-drafts';
    protected $description = 'Hapus semua draft order lama — sisakan 1 draft terbaru per meja';

    public function handle(): int
    {
        $tableIds = Order::where('status', 'draft')
            ->distinct()
            ->pluck('table_id');

        $deleted = 0;

        foreach ($tableIds as $tableId) {
            $drafts = Order::where('table_id', $tableId)
                ->where('status', 'draft')
                ->orderBy('order_id', 'desc')
                ->get();

            // Skip yang pertama (terbaru), hapus sisanya
            foreach ($drafts->skip(1) as $draft) {
                $draft->orderDetails()->delete();
                $draft->delete();
                $deleted++;
            }
        }

        $this->info("Selesai. {$deleted} draft lama dihapus.");
        return Command::SUCCESS;
    }
}