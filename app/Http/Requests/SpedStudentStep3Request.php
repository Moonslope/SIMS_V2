<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpedStudentStep3Request extends FormRequest
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
            'documents.certificate_of_diagnosis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
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

            'documents.certificate_of_diagnosis.file' => 'The certificate of diagnosis must be a file.',
            'documents.certificate_of_diagnosis.mimes' => 'The certificate of diagnosis must be a PDF, JPG, JPEG, or PNG file.',
            'documents.certificate_of_diagnosis.max' => 'The certificate of diagnosis must not exceed 5MB.',

            'documents.additional.array' => 'Additional documents must be an array.',

            'documents.additional.*.type.string' => 'The document type must be text.',
            'documents.additional.*.type.max' => 'The document type must not exceed 255 characters.',

            'documents.additional.*.file.file' => 'The additional document must be a file.',
            'documents.additional.*.file.mimes' => 'The additional document must be a PDF, JPG, JPEG, or PNG file.',
            'documents.additional.*.file.max' => 'The additional document must not exceed 5MB.',
        ];
    }
}
