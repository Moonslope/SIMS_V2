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
        return [
            'reference_number' => 'required|string|max:100|unique:payments,reference_number',
            'billing_items' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Ensure billing_items is at least an empty array
        if (!$this->has('billing_items')) {
            $this->merge([
                'billing_items' => []
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $billingItems = $this->input('billing_items', []);

            // Check if at least one item is selected
            $hasSelected = false;
            foreach ($billingItems as $itemId => $itemData) {
                if (isset($itemData['selected'])) {
                    $hasSelected = true;

                    // Validate amount for selected item
                    if (!isset($itemData['amount']) || empty($itemData['amount'])) {
                        $validator->errors()->add('billing_items', 'Amount is required for all selected fees.');
                        break;
                    }

                    if (!is_numeric($itemData['amount']) || $itemData['amount'] <= 0) {
                        $validator->errors()->add('billing_items', 'Amount must be a valid positive number.');
                        break;
                    }
                }
            }

            if (!$hasSelected) {
                $validator->errors()->add('billing_items', 'Please select at least one fee to pay.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reference_number.required' => 'Official Receipt (OR) number is required.',
            'reference_number.unique' => 'This OR number has already been used.',
            'reference_number.max' => 'OR number may not be greater than 100 characters.',
            'billing_items.required' => 'Please select at least one fee to pay.',
            'billing_items.min' => 'Please select at least one fee to pay.',
            'billing_items.*.amount.required' => 'Amount is required for selected fee.',
            'billing_items.*.amount.numeric' => 'Amount must be a number.',
            'billing_items.*.amount.min' => 'Amount must be at least 0.01.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.min' => 'Total amount must be at least 0.01.',
            'description.max' => 'Description may not be greater than 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'reference_number' => 'OR number',
            'billing_items' => 'fee items',
            'total_amount' => 'total amount',
            'description' => 'description',
        ];
    }
}
