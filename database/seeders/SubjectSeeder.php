<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Kindergarten (no subject_code column in migration)
            ['subject_name' => 'English', 'description' => 'English Language', 'grade_level_id' => 1, 'is_active' => true],
            ['subject_name' => 'Mathematics', 'description' => 'Basic Mathematics', 'grade_level_id' => 1, 'is_active' => true],
            ['subject_name' => 'Science', 'description' => 'General Science', 'grade_level_id' => 1, 'is_active' => true],
            ['subject_name' => 'Filipino', 'description' => 'Filipino Language', 'grade_level_id' => 1, 'is_active' => true],
            ['subject_name' => 'MAPEH', 'description' => 'Music, Arts, PE, and Health', 'grade_level_id' => 1, 'is_active' => true],
            
            // Grade 1
            ['subject_name' => 'English', 'description' => 'English Language', 'grade_level_id' => 2, 'is_active' => true],
            ['subject_name' => 'Mathematics', 'description' => 'Basic Mathematics', 'grade_level_id' => 2, 'is_active' => true],
            ['subject_name' => 'Science', 'description' => 'General Science', 'grade_level_id' => 2, 'is_active' => true],
            ['subject_name' => 'Filipino', 'description' => 'Filipino Language', 'grade_level_id' => 2, 'is_active' => true],
            ['subject_name' => 'MAPEH', 'description' => 'Music, Arts, PE, and Health', 'grade_level_id' => 2, 'is_active' => true],
            
            // Grade 7 - Junior High School
            ['subject_name' => 'English 7', 'description' => 'English Language and Literature', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'Mathematics 7', 'description' => 'Algebra and Geometry', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'Science 7', 'description' => 'Integrated Science', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'Filipino 7', 'description' => 'Filipino Language', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'Araling Panlipunan 7', 'description' => 'Social Studies', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'MAPEH 7', 'description' => 'Music, Arts, PE, and Health', 'grade_level_id' => 8, 'is_active' => true],
            ['subject_name' => 'TLE 7', 'description' => 'Technology and Livelihood Education', 'grade_level_id' => 8, 'is_active' => true],
            
            // Grade 11 - Senior High School (STEM Strand)
            ['subject_name' => 'English for Academic Purposes', 'description' => 'Advanced English', 'grade_level_id' => 12, 'is_active' => true],
            ['subject_name' => 'General Mathematics', 'description' => 'General Mathematics', 'grade_level_id' => 12, 'is_active' => true],
            ['subject_name' => 'Earth Science', 'description' => 'Earth and Life Science', 'grade_level_id' => 12, 'is_active' => true],
            ['subject_name' => 'Physical Education 11', 'description' => 'Physical Education', 'grade_level_id' => 12, 'is_active' => true],
            ['subject_name' => 'General Chemistry', 'description' => 'Chemistry for STEM', 'grade_level_id' => 12, 'is_active' => true],
            ['subject_name' => 'Basic Calculus', 'description' => 'Calculus for STEM', 'grade_level_id' => 12, 'is_active' => true],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
