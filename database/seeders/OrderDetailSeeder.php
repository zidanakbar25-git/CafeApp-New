<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua menu (name => id)
        $menus = DB::table('menus')->pluck('menu_id', 'name');

        $details = [
            // Order 1
            [1, 'Nasi Goreng Spesial', 2],
            [1, 'Cafe Latte', 2],
            [1, 'French Toast', 1],

            // Order 2
            [2, 'Mie Goreng Spesial', 1],
            [2, 'Iced Latte', 1],
            [2, 'Cheesecake Slice', 1],

            // Order 3
            [3, 'Spaghetti Bolognese', 1],
            [3, 'Cold Brew', 1],
            [3, 'Matcha Latte', 1],

            // Order 4
            [4, 'Avocado Toast', 1],
            [4, 'Taro Latte', 1],

            // Order 5
            [5, 'Nasi Ayam Geprek', 2],
            [5, 'Carbonara', 1],
            [5, 'Mixed Berry Smoothie', 1],
        ];

        $rows = [];

        foreach ($details as [$orderId, $menuName, $qty]) {

            // cari menu_id berdasarkan nama
            $menuId = $menus[$menuName] ?? null;

            if (!$menuId) continue; // skip kalau tidak ketemu

            // ambil harga dari database (biar selalu sync)
            $menu = DB::table('menus')->where('menu_id', $menuId)->first();

            $rows[] = [
                'order_id'   => $orderId,
                'menu_id'    => $menuId,
                'quantity'   => $qty,
                'unit_price' => $menu->price,
                'subtotal'   => $qty * $menu->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('order_details')->insert($rows);
    }
}