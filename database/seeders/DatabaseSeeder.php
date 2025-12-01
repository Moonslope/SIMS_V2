<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            // AcademicYearSeeder::class,
            // ProgramTypeSeeder::class,
            // GradeLevelSeeder::class,
            // SubjectSeeder::class,
            // TeacherSeeder::class,
            // GuardianSeeder::class,
            // StudentSeeder::class,
            // SectionSeeder::class,
            // FeeStructureSeeder::class,
            // ScheduleSeeder::class,
            // EnrollmentSeeder::class,
            // BillingSeeder::class,
            // PaymentSeeder::class,
            // SpedStudentSeeder::class,
            // DocumentSeeder::class,
            // AnnouncementSeeder::class,
        ]);
    }
}
