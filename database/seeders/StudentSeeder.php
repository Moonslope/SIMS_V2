<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\StudentGuardian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'learner_reference_number' => '123456789012',
                'first_name' => 'Juan Carlos',
                'last_name' => 'Martinez',
                'middle_name' => 'Santos',
                'birthdate' => '2015-03-15',
                'birthplace' => 'Manila',
                'gender' => 'Male',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '123 Rizal Street, Manila',
                'student_status' => 'active',
                'guardian_ids' => [1, 2],
            ],
            [
                'learner_reference_number' => '123456789013',
                'first_name' => 'Maria Clara',
                'last_name' => 'Garcia',
                'middle_name' => 'Cruz',
                'birthdate' => '2014-07-20',
                'birthplace' => 'Quezon City',
                'gender' => 'Female',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '456 Bonifacio Ave, Quezon City',
                'student_status' => 'active',
                'guardian_ids' => [3, 4],
            ],
            [
                'learner_reference_number' => '123456789014',
                'first_name' => 'Jose Miguel',
                'last_name' => 'Hernandez',
                'middle_name' => 'Ramos',
                'birthdate' => '2013-11-10',
                'birthplace' => 'Makati',
                'gender' => 'Male',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '789 Mabini Street, Makati',
                'student_status' => 'active',
                'guardian_ids' => [5, 6],
            ],
            [
                'learner_reference_number' => '123456789015',
                'first_name' => 'Ana Sofia',
                'last_name' => 'Lopez',
                'middle_name' => 'Santos',
                'birthdate' => '2011-05-25',
                'birthplace' => 'Pasig',
                'gender' => 'Female',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '321 Luna Road, Pasig',
                'student_status' => 'active',
                'guardian_ids' => [7, 8],
            ],
            [
                'learner_reference_number' => '123456789016',
                'first_name' => 'Gabriel',
                'last_name' => 'Martinez',
                'middle_name' => 'Reyes',
                'birthdate' => '2016-01-30',
                'birthplace' => 'Manila',
                'gender' => 'Male',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '123 Rizal Street, Manila',
                'student_status' => 'active',
                'guardian_ids' => [1, 2],
            ],
            [
                'learner_reference_number' => '123456789017',
                'first_name' => 'Isabella',
                'last_name' => 'Garcia',
                'middle_name' => 'Cruz',
                'birthdate' => '2009-09-15',
                'birthplace' => 'Quezon City',
                'gender' => 'Female',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '456 Bonifacio Ave, Quezon City',
                'student_status' => 'active',
                'guardian_ids' => [3, 4],
            ],
            [
                'learner_reference_number' => '123456789018',
                'first_name' => 'Sebastian',
                'last_name' => 'Hernandez',
                'middle_name' => 'Torres',
                'birthdate' => '2010-12-05',
                'birthplace' => 'Makati',
                'gender' => 'Male',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '789 Mabini Street, Makati',
                'student_status' => 'active',
                'guardian_ids' => [5, 6],
            ],
            [
                'learner_reference_number' => '123456789019',
                'first_name' => 'Valentina',
                'last_name' => 'Lopez',
                'middle_name' => 'Bautista',
                'birthdate' => '2009-04-18',
                'birthplace' => 'Pasig',
                'gender' => 'Female',
                'nationality' => 'Filipino',
                'spoken_dialect' => 'Tagalog',
                'religion' => 'Catholic',
                'address' => '321 Luna Road, Pasig',
                'student_status' => 'active',
                'guardian_ids' => [7, 8],
            ],
        ];

        foreach ($students as $studentData) {
            $guardianIds = $studentData['guardian_ids'];
            unset($studentData['guardian_ids']);
            
            $student = Student::create($studentData);
            
            // Create student-guardian relationships
            foreach ($guardianIds as $guardianId) {
                StudentGuardian::create([
                    'student_id' => $student->id,
                    'guardian_id' => $guardianId,
                ]);
            }
            
            // Generate email from first and last name
            $email = strtolower(str_replace(' ', '', $student->first_name)) . '.' . 
                     strtolower(str_replace(' ', '', $student->last_name)) . '@student.edu';
            
            // Create user account for student (no userable_id, userable_type in users table)
            User::create([
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'student',
            ]);
        }
    }
}
