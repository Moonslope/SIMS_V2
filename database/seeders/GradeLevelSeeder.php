<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class GradeLevelSeeder extends Seeder
{
    public function run(): void
    {
        $gradeLevels = [
            ['grade_name' => 'Kindergarten', 'description' => 'Kindergarten level', 'is_active' => 1],
            ['grade_name' => 'Grade 1', 'description' => 'First grade', 'is_active' => 1],
            ['grade_name' => 'Grade 2', 'description' => 'Second grade', 'is_active' => 1],
            ['grade_name' => 'Grade 3', 'description' => 'Third grade', 'is_active' => 1],
            ['grade_name' => 'Grade 4', 'description' => 'Fourth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 5', 'description' => 'Fifth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 6', 'description' => 'Sixth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 7', 'description' => 'Seventh grade', 'is_active' => 1],
            ['grade_name' => 'Grade 8', 'description' => 'Eighth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 9', 'description' => 'Ninth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 10', 'description' => 'Tenth grade', 'is_active' => 1],
            ['grade_name' => 'Grade 11', 'description' => 'Eleventh grade', 'is_active' => 1],
            ['grade_name' => 'Grade 12', 'description' => 'Twelfth grade', 'is_active' => 1],
        ];

        foreach ($gradeLevels as $grade) {
            GradeLevel::create($grade);
        }
    }
}
