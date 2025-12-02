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
            'guardians' => 'required|array|min:1',
            'guardians.*.first_name' => 'required|string|max:255',
            'guardians.*.middle_name' => 'nullable|string|max:255',
            'guardians.*.last_name' => 'required|string|max:255',
            'guardians.*.relation' => 'required|in:Father,Mother,Guardian',
            'guardians.*.contact_number' => 'required|string|max:20',
            'guardians.*.email' => 'nullable|email|max:255',
            'guardians.0.email' => 'required|email|max:255', // First guardian email is required
            'guardians.*.address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Capitalize relation for each guardian
        if ($this->has('guardians')) {
            $guardians = $this->guardians;
            foreach ($guardians as $index => $guardian) {
                if (isset($guardian['relation'])) {
                    $guardians[$index]['relation'] = ucfirst(strtolower($guardian['relation']));
                }
            }
            $this->merge(['guardians' => $guardians]);
        }
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'guardians.required' => 'At least one guardian is required.',
            'guardians.min' => 'At least one guardian is required.',
            'guardians.*.first_name.required' => 'The guardian first name is required.',
            'guardians.*.first_name.max' => 'The guardian first name must not exceed 255 characters.',
            'guardians.*.middle_name.max' => 'The guardian middle name must not exceed 255 characters.',
            'guardians.*.last_name.required' => 'The guardian last name is required.',
            'guardians.*.last_name.max' => 'The guardian last name must not exceed 255 characters.',
            'guardians.*.relation.required' => 'Please select the relationship to the student.',
            'guardians.*.relation.in' => 'The selected relationship is invalid.',
            'guardians.*.contact_number.required' => 'The contact number is required.',
            'guardians.*.contact_number.max' => 'The contact number must not exceed 20 characters.',
            'guardians.0.email.required' => 'The first guardian\'s email is required for account creation.',
            'guardians.*.email.email' => 'Please enter a valid email address.',
            'guardians.*.email.max' => 'The email address must not exceed 255 characters.',
            'guardians.*.address.max' => 'The guardian address must not exceed 500 characters.',
        ];
    }
}
