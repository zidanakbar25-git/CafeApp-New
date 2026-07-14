<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'username'   => 'Manager',
                'password'   => Hash::make('manager123'),
                'role'       => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'cashier',
                'password'   => Hash::make('cashier123'),
                'role'       => 'cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}