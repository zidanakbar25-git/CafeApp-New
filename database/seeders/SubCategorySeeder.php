<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $foodId = DB::table('categories')->where('name', 'Food')->value('category_id');
        $drinkId = DB::table('categories')->where('name', 'Drink')->value('category_id');

        $rows = [
            // Food
            [
                'category_id' => $foodId,
                'name' => 'Main Course',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $foodId,
                'name' => 'Dessert',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Drink
            [
                'category_id' => $drinkId,
                'name' => 'Coffee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $drinkId,
                'name' => 'Non-Coffee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sub_categories')->insert($rows);
    }
}