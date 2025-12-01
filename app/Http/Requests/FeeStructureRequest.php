<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeStructureRequest extends FormRequest
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
            'grade_level_id' => 'nullable|exists:grade_levels,id',
            'program_type_id' => 'required|exists:program_types,id',
            'fee_name' => 'required|string|max:100',
            'amount' => 'required|numeric',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'grade_level_id.exists' => 'The selected grade level does not exist.',

            'program_type_id.required' => 'The program type is required.',
            'program_type_id.exists' => 'The selected program type does not exist.',

            'fee_name.required' => 'The fee name is required.',
            'fee_name.string' => 'The fee name must be text.',
            'fee_name.max' => 'The fee name must not exceed 100 characters.',

            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',

            'is_active.required' => 'Please select the status for this fee structure.',
            'is_active.boolean' => 'The status must be either active or inactive.',
        ];
    }
}
