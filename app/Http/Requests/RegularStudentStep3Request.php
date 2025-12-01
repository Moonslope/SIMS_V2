<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegularStudentStep3Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'documents.birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.id_photo_1x1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.id_photo_2x2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.additional' => 'nullable|array',
            'documents.additional.*.type' => 'nullable|string|max:255',
            'documents.additional.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'documents.birth_certificate.file' => 'The birth certificate must be a file.',
            'documents.birth_certificate.mimes' => 'The birth certificate must be a PDF, JPG, JPEG, or PNG file.',
            'documents.birth_certificate.max' => 'The birth certificate must not exceed 5MB.',

            'documents.report_card.file' => 'The report card must be a file.',
            'documents.report_card.mimes' => 'The report card must be a PDF, JPG, JPEG, or PNG file.',
            'documents.report_card.max' => 'The report card must not exceed 5MB.',

            'documents.good_moral.file' => 'The good moral certificate must be a file.',
            'documents.good_moral.mimes' => 'The good moral certificate must be a PDF, JPG, JPEG, or PNG file.',
            'documents.good_moral.max' => 'The good moral certificate must not exceed 5MB.',

            'documents.id_photo_1x1.image' => 'The 1x1 ID photo must be an image.',
            'documents.id_photo_1x1.mimes' => 'The 1x1 ID photo must be a JPG, JPEG, or PNG file.',
            'documents.id_photo_1x1.max' => 'The 1x1 ID photo must not exceed 2MB.',

            'documents.id_photo_2x2.image' => 'The 2x2 ID photo must be an image.',
            'documents.id_photo_2x2.mimes' => 'The 2x2 ID photo must be a JPG, JPEG, or PNG file.',
            'documents.id_photo_2x2.max' => 'The 2x2 ID photo must not exceed 2MB.',

            'documents.additional.array' => 'Additional documents must be an array.',

            'documents.additional.*.type.string' => 'The document type must be text.',
            'documents.additional.*.type.max' => 'The document type must not exceed 255 characters.',

            'documents.additional.*.file.file' => 'The additional document must be a file.',
            'documents.additional.*.file.mimes' => 'The additional document must be a PDF, JPG, JPEG, or PNG file.',
            'documents.additional.*.file.max' => 'The additional document must not exceed 5MB.',
        ];
    }
}
