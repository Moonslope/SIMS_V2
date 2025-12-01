<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicYearRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            // Create: No exclusion needed
            $yearNameRule = 'required|string|max:50|unique:academic_years,year_name';
        } else {
            // Update: Exclude the current record's ID
            $yearNameRule = 'required|string|max:50|unique:academic_years,year_name,' . $this->route('academic_year')->id;
        }

        return [
            'year_name'   => $yearNameRule,
            'start_date'  => 'required|date|before_or_equal:end_date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'is_active'   => 'required|boolean',
            'description' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'year_name.required' => 'The academic year name is required.',
            'year_name.string' => 'The academic year name must be text.',
            'year_name.max' => 'The academic year name must not exceed 50 characters.',
            'year_name.unique' => 'This academic year name already exists.',

            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'start_date.before_or_equal' => 'The start date must be before or equal to the end date.',

            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',

            'is_active.required' => 'Please select the status for this academic year.',
            'is_active.boolean' => 'The status must be either active or inactive.',

            'description.string' => 'The description must be text.',
            'description.max' => 'The description must not exceed 255 characters.',
        ];
    }
}
