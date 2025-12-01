<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Welcome to School Year 2024-2025',
                'body' => 'We are excited to welcome all students and parents to the new school year. Classes will begin on August 15, 2024. Please ensure all enrollment requirements are submitted before the first day of classes.',
                'announcement_date' => '2024-08-01',
                'publishedBy' => 1,
            ],
            [
                'title' => 'Parent-Teacher Meeting',
                'body' => 'A mandatory parent-teacher meeting will be held on August 20, 2024, at 2:00 PM in the school auditorium. This is an opportunity to discuss your child\'s progress and expectations for the school year.',
                'announcement_date' => '2024-08-05',
                'publishedBy' => 1,
            ],
            [
                'title' => 'School Uniform Reminder',
                'body' => 'All students are reminded to wear complete school uniforms starting Monday, August 22, 2024. PE uniforms should be worn on scheduled PE days only.',
                'announcement_date' => '2024-08-10',
                'publishedBy' => 1,
            ],
            [
                'title' => 'Payment Deadline Reminder',
                'body' => 'This is a friendly reminder that the deadline for the first payment installment is September 15, 2024. Please settle your accounts on or before the due date to avoid late payment charges.',
                'announcement_date' => '2024-09-01',
                'publishedBy' => 1,
            ],
            [
                'title' => 'Intramurals Week',
                'body' => 'Our annual Intramurals Week will be held from October 7-11, 2024. All students are encouraged to participate in various sports and activities. More details to follow.',
                'announcement_date' => '2024-09-15',
                'publishedBy' => 1,
            ],
            [
                'title' => 'Midterm Examination Schedule',
                'body' => 'Midterm examinations will be conducted from October 14-18, 2024. Examination schedules will be posted on classroom bulletin boards and sent via email. Please review and prepare accordingly.',
                'announcement_date' => '2024-10-01',
                'publishedBy' => 1,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
