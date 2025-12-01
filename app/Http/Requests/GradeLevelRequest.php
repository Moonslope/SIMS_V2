<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeLevelRequest extends FormRequest
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
        $gradeId = $this->route('grade_level') ? $this->route('grade_level')->id : null;

        return [
            'grade_name'  => [
                'required',
                'string',
                'max:30',
                'unique:grade_levels,grade_name' . ($gradeId ? ',' . $gradeId : ''),
            ],
            'description'  => 'nullable|string|max:250',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules. 
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'grade_name.required' => 'The grade name field is required.',
            'grade_name.string' => 'The grade name must be text.',
            'grade_name.max' => 'The grade name must not exceed 30 characters.',
            'grade_name.unique' => 'This grade name already exists. Please choose a different name.',

            'description.string' => 'The description must be text.',
            'description.max' => 'The description must not exceed 250 characters.',

            'is_active.required' => 'Please select a status for this grade level.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
