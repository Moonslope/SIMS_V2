<?php

namespace Database\Seeders;

use App\Models\Guardian;
use Illuminate\Database\Seeder;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        $guardians = [
            [
                'first_name' => 'Roberto',
                'last_name' => 'Martinez',
                'middle_name' => 'Santos',
                'relation' => 'Father',
                'contact_number' => '09171111111',
                'email' => 'roberto.martinez@email.com',
                'address' => '123 Rizal Street, Manila',
            ],
            [
                'first_name' => 'Carmen',
                'last_name' => 'Martinez',
                'middle_name' => 'Reyes',
                'relation' => 'Mother',
                'contact_number' => '09172222222',
                'email' => 'carmen.martinez@email.com',
                'address' => '123 Rizal Street, Manila',
            ],
            [
                'first_name' => 'Luis',
                'last_name' => 'Garcia',
                'middle_name' => 'Cruz',
                'relation' => 'Father',
                'contact_number' => '09173333333',
                'email' => 'luis.garcia@email.com',
                'address' => '456 Bonifacio Ave, Quezon City',
            ],
            [
                'first_name' => 'Sofia',
                'last_name' => 'Garcia',
                'middle_name' => 'Dela Cruz',
                'relation' => 'Mother',
                'contact_number' => '09174444444',
                'email' => 'sofia.garcia@email.com',
                'address' => '456 Bonifacio Ave, Quezon City',
            ],
            [
                'first_name' => 'Antonio',
                'last_name' => 'Hernandez',
                'middle_name' => 'Ramos',
                'relation' => 'Father',
                'contact_number' => '09175555555',
                'email' => 'antonio.hernandez@email.com',
                'address' => '789 Mabini Street, Makati',
            ],
            [
                'first_name' => 'Isabel',
                'last_name' => 'Hernandez',
                'middle_name' => 'Torres',
                'relation' => 'Mother',
                'contact_number' => '09176666666',
                'email' => 'isabel.hernandez@email.com',
                'address' => '789 Mabini Street, Makati',
            ],
            [
                'first_name' => 'Miguel',
                'last_name' => 'Lopez',
                'middle_name' => 'Santos',
                'relation' => 'Father',
                'contact_number' => '09177777777',
                'email' => 'miguel.lopez@email.com',
                'address' => '321 Luna Road, Pasig',
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Lopez',
                'middle_name' => 'Bautista',
                'relation' => 'Mother',
                'contact_number' => '09178888888',
                'email' => 'ana.lopez@email.com',
                'address' => '321 Luna Road, Pasig',
            ],
        ];

        foreach ($guardians as $guardian) {
            Guardian::create($guardian);
        }
    }
}
