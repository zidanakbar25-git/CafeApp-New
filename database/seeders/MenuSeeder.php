<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [

            // MAIN COURSE
            ['Main Course', 'Nasi Goreng Spesial', 'Fried rice with egg, chicken, and vegetables', 35000, 'images/menu/nasgor.jpg'],
            ['Main Course', 'Nasi Ayam Geprek', 'Crispy smashed chicken with steamed rice', 38000, 'images/menu/geprek.jpg'],
            ['Main Course', 'Spaghetti Bolognese', 'Classic beef bolognese sauce', 45000, 'images/menu/spaghetti.jpg'],

            // DESSERT
            ['Dessert', 'Tiramisu', 'Italian coffee-flavoured dessert', 45000, 'images/menu/tiramisu.jpg'],
            ['Dessert', 'Cheesecake Slice', 'New York style baked cheesecake', 42000, 'images/menu/cheesecake.jpg'],
            ['Dessert', 'Chocolate Lava Cake', 'Warm chocolate cake with molten center', 38000, 'images/menu/lava.jpg'],

            // COFFEE
            ['Coffee', 'Espresso', 'Single / Double shot espresso', 22000, 'images/menu/espresso.jpg'],
            ['Coffee', 'Cappuccino', 'Espresso with steamed milk and foam', 28000, 'images/menu/cappuccino.jpg'],
            ['Coffee', 'Cafe Latte', 'Espresso with steamed milk', 30000, 'images/menu/latte.jpg'],

            // NON-COFFEE
            ['Non-Coffee', 'Matcha Latte', 'Japanese matcha with steamed milk', 32000, 'images/menu/matcha.jpg'],
            ['Non-Coffee', 'Chocolate', 'Rich hot / iced chocolate', 28000, 'images/menu/chocolate.jpg'],
            ['Non-Coffee', 'Orange Juice', 'Fresh-squeezed orange juice', 25000, 'images/menu/orange.jpg'],
        ];

        $rows = [];

        foreach ($menus as [$subName, $name, $desc, $price, $image]) {

            $sub = DB::table('sub_categories')
                ->where('name', $subName)
                ->first();

            if (!$sub) continue;

            $rows[] = [
                'sub_id'      => $sub->sub_id,
                'name'        => $name,
                'description' => $desc,
                'price'       => $price,
                'image_url'   => $image, // 🔥 sekarang beda-beda
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('menus')->insert($rows);
    }
}