<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'table_id'      => 1,
                'order_code'    => 'ORD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Budi Santoso',
                'status'        => 'dibatalkan',
                'total_amount'  => 110000,
                'created_at'    => Carbon::now()->subHours(3),
                'updated_at'    => Carbon::now()->subHours(2),
            ],
            [
                'table_id'      => 2,
                'order_code'    => 'ORD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Siti Rahayu',
                'status'        => 'selesai',
                'total_amount'  => 85000,
                'created_at'    => Carbon::now()->subHours(2),
                'updated_at'    => Carbon::now()->subHours(1),
            ],
            [
                'table_id'      => 3,
                'order_code'    => 'ORD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Ahmad Fauzi',
                'status'        => 'selesai',
                'total_amount'  => 93000,
                'created_at'    => Carbon::now()->subMinutes(30),
                'updated_at'    => Carbon::now()->subMinutes(20),
            ],
            [
                'table_id'      => 4,
                'order_code'    => 'ORD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Dewi Lestari',
                'status'        => 'dibatalkan',
                'total_amount'  => 67000,
                'created_at'    => Carbon::now()->subMinutes(10),
                'updated_at'    => Carbon::now()->subMinutes(10),
            ],
            [
                'table_id'      => 5,
                'order_code'    => 'ORD-' . strtoupper(Str::random(6)),
                'customer_name' => 'Rizky Pratama',
                'status'        => 'selesai',
                'total_amount'  => 128000,
                'created_at'    => Carbon::now()->subMinutes(45),
                'updated_at'    => Carbon::now()->subMinutes(40),
            ],
        ]);
         

   

    
    }
    
}