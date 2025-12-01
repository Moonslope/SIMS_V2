<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Billing;

class BillingPaymentRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     $billing = $this->route('billing');
    //     $totalPaid = $billing ? $billing->payments()->sum('amount_paid') : 0;
    //     $remainingBalance = $billing ? $billing->total_amount - $totalPaid : 0;

    //     return [
    //         'amount_paid' => [
    //             'required',
    //             'numeric',
    //             'min:0.01',
    //             'max:' . $remainingBalance
    //         ],
    //         'description' => 'nullable|string|max:500',
    //     ];
    // }

    public function rules(): array
    {
        $billing = $this->route('billing');
        $totalPaid = $billing ? $billing->payments()->sum('amount_paid') : 0;
        $remainingBalance = $billing ? $billing->total_amount - $totalPaid : PHP_INT_MAX;  // Allow any amount if no billing context

        return [
            'amount_paid' => [
                'required',
                'numeric',
                'min:0.01',
                $billing ? 'max:' . $remainingBalance : '',  // Only apply max if billing exists
            ],
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount_paid.required' => 'Payment amount is required.',
            'amount_paid.numeric' => 'Payment amount must be a number.',
            'amount_paid.min' => 'Payment amount must be at least :min.',
            'amount_paid.max' => 'Payment amount cannot exceed the remaining balance.',
            'description.max' => 'Description may not be greater than 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'amount_paid' => 'payment amount',
            'description' => 'description',
        ];
    }
}
