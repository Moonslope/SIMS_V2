<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $academicYears = [

            [
                'year_name' => '2023-2024',
                'start_date' => Carbon::create(2023, 8, 1),
                'end_date' => Carbon::create(2024, 5, 31),
                'is_active' => 0,
                'description' => 'Previous Academic Year',
            ],

        ];

        foreach ($academicYears as $year) {
            AcademicYear::create($year);
        }
    }
}
