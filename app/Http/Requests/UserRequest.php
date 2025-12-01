<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.max' => 'First name cannot exceed 100 characters.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',

            'middle_name.max' => 'Middle name cannot exceed 100 characters.',

            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'current_password.required_with' => 'Please enter your current password to change your password.',

            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Only validate current password if new password is provided
            if ($this->filled('new_password') && $this->filled('current_password')) {
                if (!Hash::check($this->current_password, Auth::user()->password)) {
                    $validator->errors()->add('current_password', 'The current password you entered is incorrect.');
                }
            }

            // Check if user entered current password but no new password
            if ($this->filled('current_password') && ! $this->filled('new_password')) {
                $validator->errors()->add('new_password', 'Please enter a new password.');
            }
        });
    }
}
