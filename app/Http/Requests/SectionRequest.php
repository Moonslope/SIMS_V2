<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
        $sectionId = $this->route('section') ? $this->route('section')->id : null;

        // For edit mode (single section)
        if ($sectionId) {
            return [
                'section_name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:sections,section_name,' . $sectionId,
                ],
                'grade_level_id' => 'required|exists:grade_levels,id',
                'academic_year_id' => 'required|exists:academic_years,id',
                'teacher_id' => 'required|exists:teachers,id',
                'capacity' => 'required|integer|min:1|max:40',
                'is_active'   => 'required|boolean',
            ];
        }

        // For create mode (multiple sections)
        return [
            'grade_level_id' => 'required|exists:grade_levels,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'sections' => 'required|array|min:1',
            'sections.*.section_name' => 'required|string|max:255',
            'sections.*.teacher_id' => 'required|exists:teachers,id',
            'sections.*.capacity' => 'required|integer|min:1|max:40',
            'sections.*.is_active' => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'section_name.required' => 'The section name is required.',
            'section_name.string' => 'The section name must be text.',
            'section_name.max' => 'The section name must not exceed 255 characters.',
            'section_name.unique' => 'This section name already exists.',

            'grade_level_id.required' => 'Please select a grade level.',
            'grade_level_id.exists' => 'The selected grade level does not exist.',

            'academic_year_id.required' => 'Please select an academic year.',
            'academic_year_id.exists' => 'The selected academic year does not exist.',

            'teacher_id.required' => 'Please select a teacher.',
            'teacher_id.exists' => 'The selected teacher does not exist.',

            'capacity.required' => 'The section capacity is required.',
            'capacity.integer' => 'The capacity must be a number.',
            'capacity.min' => 'The capacity must be at least 1.',
            'capacity.max' => 'The capacity must not exceed 40.',

            'sections.required' => 'At least one section is required.',
            'sections.array' => 'Invalid sections format.',
            'sections.min' => 'At least one section must be added.',

            'sections.*.section_name.required' => 'The section name is required.',
            'sections.*.section_name.string' => 'The section name must be text.',
            'sections.*.section_name.max' => 'The section name must not exceed 255 characters.',

            'sections.*.teacher_id.required' => 'Please select a teacher.',
            'sections.*.teacher_id.exists' => 'The selected teacher does not exist.',

            'sections.*.capacity.required' => 'The section capacity is required.',
            'sections.*.capacity.integer' => 'The capacity must be a number.',
            'sections.*.capacity.min' => 'The capacity must be at least 1.',
            'sections.*.capacity.max' => 'The capacity must not exceed 40.',

            'sections.*.is_active.required' => 'The active status is required.',
            'sections.*.is_active.boolean' => 'The active status must be true or false.',

            'is_active.required' => 'Please select the status for this section.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
