<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'middle_name' => 'Cruz',
                'contact_number' => '09171234567',
                'address' => '123 Main St, Manila',
                'is_active' => true,
            ],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'middle_name' => 'Garcia',
                'contact_number' => '09181234567',
                'address' => '456 Secondary Ave, Quezon City',
                'is_active' => true,
            ],
            [
                'first_name' => 'Rosa',
                'last_name' => 'Reyes',
                'middle_name' => 'Bautista',
                'contact_number' => '09191234567',
                'address' => '789 Third St, Makati',
                'is_active' => true,
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Gonzales',
                'middle_name' => 'Torres',
                'contact_number' => '09201234567',
                'address' => '321 Fourth Rd, Pasig',
                'is_active' => true,
            ],
            [
                'first_name' => 'Elena',
                'last_name' => 'Mendoza',
                'middle_name' => 'Ramos',
                'contact_number' => '09211234567',
                'address' => '654 Fifth Ave, Taguig',
                'is_active' => true,
            ],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = Teacher::create($teacherData);
            
            // Create user account for teacher (no email, date_of_birth, gender, specialization, employment_status, hire_date in teachers table)
            $email = strtolower(str_replace(' ', '', $teacherData['first_name']) . '.' . str_replace(' ', '', $teacherData['last_name'])) . '@school.edu';
            User::create([
                'first_name' => $teacherData['first_name'],
                'middle_name' => $teacherData['middle_name'],
                'last_name' => $teacherData['last_name'],
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'teacher',
            ]);
        }
    }
}
