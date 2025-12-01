<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize enrollment_status to lowercase for PostgreSQL enum
        if ($this->has('enrollment_status')) {
            $this->merge([
                'enrollment_status' => strtolower($this->enrollment_status),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'grade_level_id' => 'nullable|exists:grade_levels,id',
            'program_type_id' => 'required|exists:program_types,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'student_id' => 'required|exists:students,id',
            'date_enrolled' => 'required|date',
            'enrollment_status' => 'required|string',
            'createdBy' => 'required|string',
        ];

        // For updates, exclude the current enrollment ID
        if ($this->method() !== 'POST') {
            $rules['student_id'] .= ',' . $this->route('enrollment')->id . ',id';
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'grade_level_id.exists' => 'The selected grade level does not exist.',

            'program_type_id.required' => 'The program type is required.',
            'program_type_id.exists' => 'The selected program type does not exist.',

            'section_id.exists' => 'The selected section does not exist.',

            'academic_year_id.required' => 'The academic year is required.',
            'academic_year_id.exists' => 'The selected academic year does not exist.',

            'student_id.required' => 'The student is required.',
            'student_id.exists' => 'The selected student does not exist.',

            'date_enrolled.required' => 'The enrollment date is required.',
            'date_enrolled.date' => 'The enrollment date must be a valid date.',

            'enrollment_status.required' => 'The enrollment status is required.',
            'enrollment_status.string' => 'The enrollment status must be text.',

            'createdBy.required' => 'The creator is required.',
            'createdBy.string' => 'The creator name must be text.',
        ];
    }
}
