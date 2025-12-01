<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BillingItem;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        $billings = [
            [
                'enrollment_id' => 1,
                'created_date' => '2024-08-15',
                'total_amount' => 21500.00,
                'status' => 'paid',
                'items' => [
                    ['fee_structure_id' => 3, 'amount' => 18000.00],
                    ['fee_structure_id' => 4, 'amount' => 3500.00],
                ],
            ],
            [
                'enrollment_id' => 2,
                'created_date' => '2024-08-15',
                'total_amount' => 21500.00,
                'status' => 'partial',
                'items' => [
                    ['fee_structure_id' => 3, 'amount' => 18000.00],
                    ['fee_structure_id' => 4, 'amount' => 3500.00],
                ],
            ],
            [
                'enrollment_id' => 3,
                'created_date' => '2024-08-16',
                'total_amount' => 21500.00,
                'status' => 'pending',
                'items' => [
                    ['fee_structure_id' => 3, 'amount' => 18000.00],
                    ['fee_structure_id' => 4, 'amount' => 3500.00],
                ],
            ],
            [
                'enrollment_id' => 4,
                'created_date' => '2024-08-15',
                'total_amount' => 33000.00,
                'status' => 'paid',
                'items' => [
                    ['fee_structure_id' => 5, 'amount' => 25000.00],
                    ['fee_structure_id' => 6, 'amount' => 5000.00],
                    ['fee_structure_id' => 7, 'amount' => 3000.00],
                ],
            ],
            [
                'enrollment_id' => 5,
                'created_date' => '2024-08-15',
                'total_amount' => 18000.00,
                'status' => 'paid',
                'items' => [
                    ['fee_structure_id' => 1, 'amount' => 15000.00],
                    ['fee_structure_id' => 2, 'amount' => 3000.00],
                ],
            ],
            [
                'enrollment_id' => 6,
                'created_date' => '2024-08-15',
                'total_amount' => 41000.00,
                'status' => 'partial',
                'items' => [
                    ['fee_structure_id' => 8, 'amount' => 30000.00],
                    ['fee_structure_id' => 9, 'amount' => 6000.00],
                    ['fee_structure_id' => 10, 'amount' => 5000.00],
                ],
            ],
        ];

        foreach ($billings as $billingData) {
            $items = $billingData['items'];
            unset($billingData['items']);
            
            $billing = Billing::create($billingData);
            
            // Create billing items (no description column in billing_items table)
            foreach ($items as $item) {
                BillingItem::create([
                    'billing_id' => $billing->id,
                    'fee_structure_id' => $item['fee_structure_id'],
                    'amount' => $item['amount'],
                ]);
            }
        }
    }
}
