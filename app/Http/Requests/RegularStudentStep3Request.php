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
            'documents.form_137' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.certification' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.birth_certificate_copy' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
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
            'documents.form_137.file' => 'Form 137 must be a file.',
            'documents.form_137.mimes' => 'Form 137 must be a PDF, JPG, JPEG, or PNG file.',
            'documents.form_137.max' => 'Form 137 must not exceed 5MB.',

            'documents.certification.file' => 'The certification must be a file.',
            'documents.certification.mimes' => 'The certification must be a PDF, JPG, JPEG, or PNG file.',
            'documents.certification.max' => 'The certification must not exceed 5MB.',

            'documents.birth_certificate_copy.file' => 'The photocopy of birth certificate must be a file.',
            'documents.birth_certificate_copy.mimes' => 'The photocopy of birth certificate must be a PDF, JPG, JPEG, or PNG file.',
            'documents.birth_certificate_copy.max' => 'The photocopy of birth certificate must not exceed 5MB.',

            'documents.additional.array' => 'Additional documents must be an array.',

            'documents.additional.*.type.string' => 'The document type must be text.',
            'documents.additional.*.type.max' => 'The document type must not exceed 255 characters.',

            'documents.additional.*.file.file' => 'The additional document must be a file.',
            'documents.additional.*.file.mimes' => 'The additional document must be a PDF, JPG, JPEG, or PNG file.',
            'documents.additional.*.file.max' => 'The additional document must not exceed 5MB.',
        ];
    }
}
