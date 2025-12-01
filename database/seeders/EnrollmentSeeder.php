<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Models\EnrollmentSchedule;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = [
            [
                'student_id' => 1, // Juan Carlos Martinez - Grade 1
                'academic_year_id' => 1,
                'grade_level_id' => 2,
                'section_id' => 3,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-15',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [6, 7, 8, 9, 10], // Grade 1 subjects
                'schedules' => [1, 2, 3],
            ],
            [
                'student_id' => 2, // Maria Clara Garcia - Grade 2
                'academic_year_id' => 1,
                'grade_level_id' => 3,
                'section_id' => 5,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-15',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [6, 7, 8, 9, 10],
                'schedules' => [],
            ],
            [
                'student_id' => 3, // Jose Miguel Hernandez - Grade 2
                'academic_year_id' => 1,
                'grade_level_id' => 3,
                'section_id' => 6,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-16',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [6, 7, 8, 9, 10],
                'schedules' => [],
            ],
            [
                'student_id' => 4, // Ana Sofia Lopez - Grade 7
                'academic_year_id' => 1,
                'grade_level_id' => 8,
                'section_id' => 7,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-15',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [11, 12, 13, 14, 15, 16, 17],
                'schedules' => [4, 5, 6],
            ],
            [
                'student_id' => 5, // Gabriel Martinez - Kindergarten
                'academic_year_id' => 1,
                'grade_level_id' => 1,
                'section_id' => 1,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-15',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [1, 2, 3, 4, 5],
                'schedules' => [],
            ],
            [
                'student_id' => 6, // Isabella Garcia - Grade 11
                'academic_year_id' => 1,
                'grade_level_id' => 12,
                'section_id' => 9,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-15',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [18, 19, 20, 21, 22, 23],
                'schedules' => [7, 8, 9, 10],
            ],
            [
                'student_id' => 7, // Sebastian Hernandez - Grade 7
                'academic_year_id' => 1,
                'grade_level_id' => 8,
                'section_id' => 8,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-16',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [11, 12, 13, 14, 15, 16, 17],
                'schedules' => [],
            ],
            [
                'student_id' => 8, // Valentina Lopez - Grade 11
                'academic_year_id' => 1,
                'grade_level_id' => 12,
                'section_id' => 10,
                'program_type_id' => 1,
                'date_enrolled' => '2024-08-16',
                'enrollment_status' => 'enrolled',
                'createdBy' => 1,
                'subjects' => [18, 19, 20, 21, 22, 23],
                'schedules' => [],
            ],
        ];

        foreach ($enrollments as $enrollmentData) {
            $subjects = $enrollmentData['subjects'];
            $schedules = $enrollmentData['schedules'];
            unset($enrollmentData['subjects']);
            unset($enrollmentData['schedules']);
            
            $enrollment = Enrollment::create($enrollmentData);
            
            // Create enrollment subjects
            foreach ($subjects as $subjectId) {
                EnrollmentSubject::create([
                    'enrollment_id' => $enrollment->id,
                    'subject_id' => $subjectId,
                ]);
            }
            
            // Create enrollment schedules
            foreach ($schedules as $scheduleId) {
                EnrollmentSchedule::create([
                    'enrollment_id' => $enrollment->id,
                    'schedule_id' => $scheduleId,
                ]);
            }
        }
    }
}
