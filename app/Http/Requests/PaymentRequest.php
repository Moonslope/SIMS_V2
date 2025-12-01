<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'billing_id' => 'required|exists:billings,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'billing_id.required' => 'Billing ID is required.',
            'billing_id.exists' => 'The selected billing does not exist.',
            'academic_year_id.required' => 'Academic year is required.',
            'academic_year_id.exists' => 'The selected academic year does not exist.',
            'amount_paid.required' => 'Payment amount is required.',
            'amount_paid.numeric' => 'Payment amount must be a number.',
            'amount_paid.min' => 'Payment amount must be at least :min.',
            'description.max' => 'Description may not be greater than 500 characters.',
        ];
    }
}
