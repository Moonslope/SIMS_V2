<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [
            [
                'billing_id' => 1,
                'amount_paid' => 21500.00,
                'payment_date' => '2024-08-15',
                'reference_number' => 'PAY-2024-0001',
                'academic_year_id' => 1,
                'description' => 'Full payment for Grade 1 enrollment',
            ],
            [
                'billing_id' => 2,
                'amount_paid' => 10000.00,
                'payment_date' => '2024-08-15',
                'reference_number' => 'PAY-2024-0002',
                'academic_year_id' => 1,
                'description' => 'Partial payment - Down payment',
            ],
            [
                'billing_id' => 4,
                'amount_paid' => 33000.00,
                'payment_date' => '2024-08-15',
                'reference_number' => 'PAY-2024-0003',
                'academic_year_id' => 1,
                'description' => 'Full payment for Grade 7 enrollment',
            ],
            [
                'billing_id' => 5,
                'amount_paid' => 18000.00,
                'payment_date' => '2024-08-15',
                'reference_number' => 'PAY-2024-0004',
                'academic_year_id' => 1,
                'description' => 'Full payment for Kindergarten enrollment',
            ],
            [
                'billing_id' => 6,
                'amount_paid' => 20000.00,
                'payment_date' => '2024-08-15',
                'reference_number' => 'PAY-2024-0005',
                'academic_year_id' => 1,
                'description' => 'Partial payment - Down payment',
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
