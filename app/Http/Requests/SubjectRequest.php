<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
        $subjectId = $this->route('subject') ? $this->route('subject')->id : null;
        return [
            'subject_name' => [
                'required',
                'string',
                'max:255',
                // Unique combination of subject_name AND grade_level_id
                'unique:subjects,subject_name,' . $subjectId . ',id,grade_level_id,' . $this->grade_level_id
            ],
            'description' => 'nullable|string|max:500',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'subject_name.required' => 'The subject name is required.',
            'subject_name.string' => 'The subject name must be text.',
            'subject_name.max' => 'The subject name must not exceed 255 characters.',
            'subject_name.unique' => 'This subject name already exists for the selected grade level.',

            'description.string' => 'The description must be text.',
            'description.max' => 'The description must not exceed 500 characters.',

            'grade_level_id.required' => 'Please select a grade level.',
            'grade_level_id.exists' => 'The selected grade level does not exist.',

            'is_active.required' => 'Please select the status for this subject.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
