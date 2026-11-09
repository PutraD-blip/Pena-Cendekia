<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'bank_name' => 'BCA (Bank Central Asia)',
                'account_number' => '1234567890',
                'account_name' => 'PT. PENA CENDEKIA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1370012345678',
                'account_name' => 'PT. PENA CENDEKIA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'BRI (Bank Rakyat Indonesia)',
                'account_number' => '012301001234530',
                'account_name' => 'PT. PENA CENDEKIA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_accounts')->insert($accounts);
    }
}
