<?php

namespace Database\Seeders;

use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $feeStructures = [
            // Kindergarten fees
            [
                'fee_name' => 'Tuition Fee',
                'amount' => 15000.00,
                'grade_level_id' => 1,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Miscellaneous Fee',
                'amount' => 3000.00,
                'grade_level_id' => 1,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            
            // Grade 1 fees
            [
                'fee_name' => 'Tuition Fee',
                'amount' => 18000.00,
                'grade_level_id' => 2,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Miscellaneous Fee',
                'amount' => 3500.00,
                'grade_level_id' => 2,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            
            // Grade 7 fees
            [
                'fee_name' => 'Tuition Fee',
                'amount' => 25000.00,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Miscellaneous Fee',
                'amount' => 5000.00,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Laboratory Fee',
                'amount' => 3000.00,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            
            // Grade 11 STEM fees
            [
                'fee_name' => 'Tuition Fee',
                'amount' => 30000.00,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Miscellaneous Fee',
                'amount' => 6000.00,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Laboratory Fee',
                'amount' => 5000.00,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'is_active' => true,
            ],
            
            // SPED Program fees
            [
                'fee_name' => 'Tuition Fee',
                'amount' => 20000.00,
                'grade_level_id' => 1,
                'program_type_id' => 2,
                'is_active' => true,
            ],
            [
                'fee_name' => 'Therapy Fee',
                'amount' => 8000.00,
                'grade_level_id' => 1,
                'program_type_id' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($feeStructures as $fee) {
            FeeStructure::create($fee);
        }
    }
}
