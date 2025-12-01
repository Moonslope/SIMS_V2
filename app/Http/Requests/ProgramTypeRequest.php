<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramTypeRequest extends FormRequest
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
        $programId = $this->route('program_type') ?  $this->route('program_type')->id : null;
        return [
            'program_name'  => 'required|string|max:50|unique:program_types,program_name,' . $programId,
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
            'program_name.required' => 'The program name field is required.',
            'program_name.string' => 'The program name must be text.',
            'program_name.max' => 'The program name must not exceed 50 characters.',
            'program_name.unique' => 'This program name already exists. Please choose a different name.',

            'description.string' => 'The description must be text.',
            'description.max' => 'The description must not exceed 250 characters.',

            'is_active.required' => 'Please select a status for this program.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
