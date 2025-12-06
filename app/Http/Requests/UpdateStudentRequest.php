<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Student Information
            'learner_reference_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'nickname' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'birthplace' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'nationality' => 'nullable|string|max:255',
            'spoken_dialect' => 'nullable|string|max:255',
            'other_spoken_dialect' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            
            // Guardian Information (if provided)
            'guardians' => 'nullable|array',
            'guardians.*.id' => 'nullable|exists:guardians,id',
            'guardians.*.first_name' => 'required_with:guardians|string|max:255',
            'guardians.*.middle_name' => 'nullable|string|max:255',
            'guardians.*.last_name' => 'required_with:guardians|string|max:255',
            'guardians.*.relationship' => 'required_with:guardians|string|max:255',
            'guardians.*.contact_number' => 'required_with:guardians|string|max:20',
            'guardians.*.email' => 'nullable|email|max:255',
            'guardians.*.address' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'learner_reference_number.required' => 'Learner Reference Number is required.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'birthdate.required' => 'Birthdate is required.',
            'gender.required' => 'Gender is required.',
            'guardians.*.first_name.required_with' => 'Guardian first name is required.',
            'guardians.*.last_name.required_with' => 'Guardian last name is required.',
            'guardians.*.relationship.required_with' => 'Guardian relationship is required.',
            'guardians.*.contact_number.required_with' => 'Guardian contact number is required.',
        ];
    }
}
