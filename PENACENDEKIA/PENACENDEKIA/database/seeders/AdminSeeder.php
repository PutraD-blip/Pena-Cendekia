<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Account
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Administrator Pena Cendikia',
            'email' => 'admin@penacendikia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Designer Account
        $designerId = DB::table('users')->insertGetId([
            'name' => 'Tim Desain Pena Cendikia',
            'email' => 'desain@penacendikia.com',
            'password' => Hash::make('desain123'),
            'role' => 'designer',
            'phone' => '081298765432',
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
