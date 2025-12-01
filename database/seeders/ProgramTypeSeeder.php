<?php

namespace Database\Seeders;

use App\Models\ProgramType;
use Illuminate\Database\Seeder;

class ProgramTypeSeeder extends Seeder
{
    public function run(): void
    {
        $programTypes = [
            [
                'program_name' => 'Regular Program',
                'description' => 'Standard curriculum for all students',
                'is_active' => 1,
            ],
            [
                'program_name' => 'SPED Program',
                'description' => 'Special Education Program for students with special needs',
                'is_active' => 1,
            ],
            [
                'program_name' => 'Advanced Program',
                'description' => 'Accelerated learning program for gifted students',
                'is_active' => 1,
            ],
            [
                'program_name' => 'Remedial Program',
                'description' => 'Additional support program for struggling students',
                'is_active' => 1,
            ],
        ];

        foreach ($programTypes as $program) {
            ProgramType::create($program);
        }
    }
}
