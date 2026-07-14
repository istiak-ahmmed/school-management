<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['en_name' => 'Cash', 'bn_name' => 'ক্যাশ', 'is_system' => 1],
            ['en_name' => 'Bank', 'bn_name' => 'ব্যাংক', 'is_system' => 1],
            ['en_name' => 'Bkash', 'bn_name' => 'বিকাশ', 'is_system' => 0],
            ['en_name' => 'Nagad', 'bn_name' => 'নগদ', 'is_system' => 0],
            ['en_name' => 'Rocket', 'bn_name' => 'রকেট', 'is_system' => 0],
            ['en_name' => 'Upay', 'bn_name' => 'উপায়', 'is_system' => 0],
            ['en_name' => 'Cheque', 'bn_name' => 'চেক', 'is_system' => 0],
            ['en_name' => 'SSLCommerz', 'bn_name' => 'SSLCommerz', 'is_system' => 0],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['en_name' => $method['en_name']],
                [
                    'bn_name' => $method['bn_name'],
                    'is_system' => $method['is_system'],
                    'status' => 1,
                ]
            );
        }
    }
}
