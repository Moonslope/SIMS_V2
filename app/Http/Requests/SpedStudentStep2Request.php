<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpedStudentStep2Request extends FormRequest
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
            'guardian_first_name' => 'required|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_last_name' => 'required|string|max:255',
            'relation' => 'required|in:Father,Mother,Guardian',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'guardian_address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Capitalize relation
        if ($this->relation) {
            $this->merge([
                'relation' => ucfirst(strtolower($this->relation))
            ]);
        }
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'guardian_first_name.required' => 'The guardian first name is required.',
            'guardian_first_name.string' => 'The guardian first name must be text.',
            'guardian_first_name.max' => 'The guardian first name must not exceed 255 characters.',

            'guardian_middle_name.string' => 'The guardian middle name must be text.',
            'guardian_middle_name.max' => 'The guardian middle name must not exceed 255 characters.',

            'guardian_last_name.required' => 'The guardian last name is required.',
            'guardian_last_name.string' => 'The guardian last name must be text.',
            'guardian_last_name.max' => 'The guardian last name must not exceed 255 characters.',

            'relation.required' => 'Please select the relationship to the student.',
            'relation.in' => 'The selected relationship is invalid.',

            'contact_number.required' => 'The contact number is required.',
            'contact_number.string' => 'The contact number must be text.',
            'contact_number.max' => 'The contact number must not exceed 20 characters.',

            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',

            'guardian_address.string' => 'The guardian address must be text.',
            'guardian_address.max' => 'The guardian address must not exceed 500 characters.',
        ];
    }
}
