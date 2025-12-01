<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // Kindergarten sections
            ['section_name' => 'K-Section A', 'grade_level_id' => 1, 'academic_year_id' => 1, 'teacher_id' => 1, 'capacity' => 25, 'is_active' => true],
            ['section_name' => 'K-Section B', 'grade_level_id' => 1, 'academic_year_id' => 1, 'teacher_id' => 2, 'capacity' => 25, 'is_active' => true],
            
            // Grade 1 sections
            ['section_name' => 'Grade 1-Section A', 'grade_level_id' => 2, 'academic_year_id' => 1, 'teacher_id' => 1, 'capacity' => 30, 'is_active' => true],
            ['section_name' => 'Grade 1-Section B', 'grade_level_id' => 2, 'academic_year_id' => 1, 'teacher_id' => 2, 'capacity' => 30, 'is_active' => true],
            
            // Grade 2 sections
            ['section_name' => 'Grade 2-Section A', 'grade_level_id' => 3, 'academic_year_id' => 1, 'teacher_id' => 3, 'capacity' => 30, 'is_active' => true],
            ['section_name' => 'Grade 2-Section B', 'grade_level_id' => 3, 'academic_year_id' => 1, 'teacher_id' => 4, 'capacity' => 30, 'is_active' => true],
            
            // Grade 7 sections
            ['section_name' => 'Grade 7-Section A', 'grade_level_id' => 8, 'academic_year_id' => 1, 'teacher_id' => 1, 'capacity' => 35, 'is_active' => true],
            ['section_name' => 'Grade 7-Section B', 'grade_level_id' => 8, 'academic_year_id' => 1, 'teacher_id' => 2, 'capacity' => 35, 'is_active' => true],
            
            // Grade 11 STEM sections
            ['section_name' => 'Grade 11-STEM A', 'grade_level_id' => 12, 'academic_year_id' => 1, 'teacher_id' => 3, 'capacity' => 40, 'is_active' => true],
            ['section_name' => 'Grade 11-STEM B', 'grade_level_id' => 12, 'academic_year_id' => 1, 'teacher_id' => 4, 'capacity' => 40, 'is_active' => true],
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
