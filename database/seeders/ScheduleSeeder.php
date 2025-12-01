<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            // Grade 1 schedules
            [
                'subject_id' => 6,
                'grade_level_id' => 2,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 7,
                'grade_level_id' => 2,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 8,
                'grade_level_id' => 2,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Tuesday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'is_active' => true,
            ],
            
            // Grade 7 schedules
            [
                'subject_id' => 11,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 12,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 13,
                'grade_level_id' => 8,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Tuesday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'is_active' => true,
            ],
            
            // Grade 11 STEM schedules
            [
                'subject_id' => 18,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 19,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Monday',
                'start_time' => '09:30:00',
                'end_time' => '11:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 22,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Tuesday',
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'is_active' => true,
            ],
            [
                'subject_id' => 23,
                'grade_level_id' => 12,
                'program_type_id' => 1,
                'academic_year_id' => 1,
                'day_of_the_week' => 'Wednesday',
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
