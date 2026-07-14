<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'order_id'       => 1,
                'admin_id'       => 2, // cashier1
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'paid_at'        => Carbon::now()->subHours(2),
                'created_at'     => Carbon::now()->subHours(2),
                'updated_at'     => Carbon::now()->subHours(2),
            ],
            [
                'order_id'       => 2,
                'admin_id'       => 2, // cashier1
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_at'        => Carbon::now()->subHour(),
                'created_at'     => Carbon::now()->subHour(),
                'updated_at'     => Carbon::now()->subHour(),
            ],
            [
                'order_id'       => 3,
                'admin_id'       => 2, // cashier1
                'payment_method' => 'qris',
                'payment_status' => 'pending',
                'paid_at'        => null,
                'created_at'     => Carbon::now()->subMinutes(20),
                'updated_at'     => Carbon::now()->subMinutes(20),
            ],
        ]);
    }
}