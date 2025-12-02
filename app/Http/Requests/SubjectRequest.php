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

        // For edit mode (single subject)
        if ($subjectId) {
            $uniqueRule = 'unique:subjects,subject_name,' . $subjectId . ',id,grade_level_id,' . $this->grade_level_id;

            return [
                'subject_name' => [
                    'required',
                    'string',
                    'max:255',
                    $uniqueRule
                ],
                'description' => 'nullable|string|max:500',
                'grade_level_id' => 'required|exists:grade_levels,id',
                'is_active'   => 'required|boolean',
            ];
        }

        // For create mode (multiple subjects)
        return [
            'grade_level_id' => 'required|exists:grade_levels,id',
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_name' => 'required|string|max:255',
            'subjects.*.description' => 'nullable|string|max:500',
            'subjects.*.is_active' => 'required|boolean',
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

            'subjects.required' => 'At least one subject is required.',
            'subjects.array' => 'Invalid subjects format.',
            'subjects.min' => 'At least one subject must be added.',

            'subjects.*.subject_name.required' => 'The subject name is required.',
            'subjects.*.subject_name.string' => 'The subject name must be text.',
            'subjects.*.subject_name.max' => 'The subject name must not exceed 255 characters.',

            'subjects.*.description.string' => 'The description must be text.',
            'subjects.*.description.max' => 'The description must not exceed 500 characters.',

            'subjects.*.is_active.required' => 'The active status is required.',
            'subjects.*.is_active.boolean' => 'The active status must be true or false.',

            'is_active.required' => 'Please select the status for this subject.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
