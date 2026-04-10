<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        if (! $admin) {
            DB::table('users')->insert([
                'username' => 'Admin',
                'email' => 'admin@admin.com',
                'role' => 'admin',
                'password' => Hash::make('Zuri0ch2026$'),
            ]);
        }

        // Create seller user
        $seller = DB::table('users')->where('email', 'seller@seller.com')->first();

        if (!$seller) {
            DB::table('users')->insert([
                'username' => 'Seller',
                'email' => 'seller@seller.com',
                'role' => 'seller',
                'password' => Hash::make('Zuri0ch2026$'),
            ]);
        }
    }
}
