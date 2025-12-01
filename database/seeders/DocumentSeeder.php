<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'student_id' => 1,
                'document_type' => 'Birth Certificate',
                'document_name' => 'birth_certificate_juan_martinez.pdf',
                'file_path' => 'documents/students/1/birth_certificate_juan_martinez.pdf',
            ],
            [
                'student_id' => 1,
                'document_type' => 'Report Card',
                'document_name' => 'report_card_kindergarten.pdf',
                'file_path' => 'documents/students/1/report_card_kindergarten.pdf',
            ],
            [
                'student_id' => 2,
                'document_type' => 'Birth Certificate',
                'document_name' => 'birth_certificate_maria_garcia.pdf',
                'file_path' => 'documents/students/2/birth_certificate_maria_garcia.pdf',
            ],
            [
                'student_id' => 2,
                'document_type' => 'Medical Certificate',
                'document_name' => 'medical_certificate_maria_garcia.pdf',
                'file_path' => 'documents/students/2/medical_certificate_maria_garcia.pdf',
            ],
            [
                'student_id' => 4,
                'document_type' => 'Birth Certificate',
                'document_name' => 'birth_certificate_ana_lopez.pdf',
                'file_path' => 'documents/students/4/birth_certificate_ana_lopez.pdf',
            ],
            [
                'student_id' => 4,
                'document_type' => 'Report Card',
                'document_name' => 'report_card_grade6.pdf',
                'file_path' => 'documents/students/4/report_card_grade6.pdf',
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
