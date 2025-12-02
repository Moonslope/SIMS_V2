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
            'fees' => 'required|array|min:1',
            'fees.*.fee_name' => 'required|string|max:100',
            'fees.*.amount' => 'required|numeric|min:0',
            'fees.*.is_active' => 'required|boolean',
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

            'fees.required' => 'At least one fee is required.',
            'fees.array' => 'Invalid fees format.',
            'fees.min' => 'At least one fee must be added.',

            'fees.*.fee_name.required' => 'The fee name is required.',
            'fees.*.fee_name.string' => 'The fee name must be text.',
            'fees.*.fee_name.max' => 'The fee name must not exceed 100 characters.',

            'fees.*.amount.required' => 'The amount is required.',
            'fees.*.amount.numeric' => 'The amount must be a number.',
            'fees.*.amount.min' => 'The amount must be at least 0.',

            'fees.*.is_active.required' => 'The active status is required.',
            'fees.*.is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
