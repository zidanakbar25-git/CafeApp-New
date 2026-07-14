<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Food',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Drink', 'created_at' => now(), 'updated_at' => now()],
           
        ]);
    }
}