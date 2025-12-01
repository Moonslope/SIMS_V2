<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user'); // Get the user ID from route parameter

        return [
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'role'        => ['required', 'in:admin,registrar,cashier,student'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter the first name.',
            'first_name.max' => 'First name cannot exceed 100 characters.',

            'last_name.required' => 'Please enter the last name.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',

            'middle_name.max' => 'Middle name cannot exceed 100 characters.',

            'email.required' => 'Please enter the email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'role.required' => 'Please select a role.',
            'role.in' => 'Please select a valid role.',
        ];
    }
}
