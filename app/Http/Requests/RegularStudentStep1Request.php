<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class RegularStudentStep1Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'learner_reference_number' => 'required|digits:12|unique:students,learner_reference_number',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date|before_or_equal:' . Carbon::now()->subYears(4)->format('Y-m-d'),
            'birthplace' => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'spoken_dialect' => 'required|string|max:100',
            'other_spoken_dialect' => 'nullable|string|max:100',
            'religion' => 'required|string|max:100',
            'address' => 'required|string|max:500',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Capitalize gender
        if ($this->gender) {
            $this->merge([
                'gender' => ucfirst(strtolower($this->gender))
            ]);
        }
    }

    public function messages()
    {
        return [
            'learner_reference_number.required' => 'The Learner Reference Number (LRN) is required.',
            'learner_reference_number.digits' => 'The LRN must be exactly 12 digits.',
            'learner_reference_number.unique' => 'This LRN is already registered in the system.',

            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be text.',
            'first_name.max' => 'The first name must not exceed 255 characters.',

            'middle_name.string' => 'The middle name must be text.',
            'middle_name.max' => 'The middle name must not exceed 255 characters.',

            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be text.',
            'last_name.max' => 'The last name must not exceed 255 characters.',

            'extension_name.string' => 'The extension name must be text.',
            'extension_name.max' => 'The extension name must not exceed 50 characters.',

            'nickname.string' => 'The nickname must be text.',
            'nickname.max' => 'The nickname must not exceed 100 characters.',

            'gender.required' => 'Please select a gender.',
            'gender.in' => 'The selected gender is invalid.',

            'birthdate.required' => 'The birthdate is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
            'birthdate.before_or_equal' => 'The student must be at least 4 years old.',

            'birthplace.required' => 'The birthplace is required.',
            'birthplace.string' => 'The birthplace must be text.',
            'birthplace.max' => 'The birthplace must not exceed 255 characters.',

            'nationality.required' => 'The nationality is required.',
            'nationality.string' => 'The nationality must be text.',
            'nationality.max' => 'The nationality must not exceed 100 characters.',

            'spoken_dialect.required' => 'The spoken dialect is required.',
            'spoken_dialect.string' => 'The spoken dialect must be text.',
            'spoken_dialect.max' => 'The spoken dialect must not exceed 100 characters.',

            'other_spoken_dialect.string' => 'The other spoken dialect must be text.',
            'other_spoken_dialect.max' => 'The other spoken dialect must not exceed 100 characters.',

            'religion.required' => 'The religion is required.',
            'religion.string' => 'The religion must be text.',
            'religion.max' => 'The religion must not exceed 100 characters.',

            'address.required' => 'The address is required.',
            'address.string' => 'The address must be text.',
            'address.max' => 'The address must not exceed 500 characters.',
        ];
    }
}
