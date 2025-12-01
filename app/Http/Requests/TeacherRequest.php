<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'contact_number' => ['required', 'string', 'max:11'],
            'address'   => ['required', 'string', 'max:100'],
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be text.',
            'first_name.max' => 'The first name must not exceed 100 characters.',

            'middle_name.string' => 'The middle name must be text.',
            'middle_name.max' => 'The middle name must not exceed 100 characters.',

            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be text.',
            'last_name.max' => 'The last name must not exceed 100 characters.',

            'contact_number.required' => 'The contact number is required.',
            'contact_number.string' => 'The contact number must be text.',
            'contact_number.max' => 'The contact number must not exceed 11 characters.',

            'address.required' => 'The address is required.',
            'address.string' => 'The address must be text.',
            'address.max' => 'The address must not exceed 100 characters.',

            'is_active.required' => 'Please select the status for this teacher.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
