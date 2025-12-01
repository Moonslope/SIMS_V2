<?php

namespace Database\Seeders;

use App\Models\SpedStudent;
use Illuminate\Database\Seeder;

class SpedStudentSeeder extends Seeder
{
    public function run(): void
    {
        $spedStudents = [
            [
                'student_id' => 5, // Gabriel Martinez
                'type_of_disability' => 'Learning Disability',
                'date_of_diagnosis' => '2023-03-15',
                'cause_of_disability' => 'Developmental',
            ],
            [
                'student_id' => 1, // Juan Carlos Martinez
                'type_of_disability' => 'ADHD',
                'date_of_diagnosis' => '2022-06-20',
                'cause_of_disability' => 'Neurological',
            ],
        ];

        foreach ($spedStudents as $spedStudent) {
            SpedStudent::create($spedStudent);
        }
    }
}
