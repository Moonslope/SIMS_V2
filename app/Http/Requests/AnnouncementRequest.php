<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'announcement_date' => 'required|date',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'The user is required.',
            'user_id.exists' => 'The selected user does not exist.',

            'title.required' => 'The announcement title is required.',
            'title.string' => 'The title must be text.',
            'title.max' => 'The title must not exceed 255 characters.',

            'body.required' => 'The announcement body is required.',
            'body.string' => 'The announcement body must be text.',

            'announcement_date.required' => 'The announcement date is required.',
            'announcement_date.date' => 'The announcement date must be a valid date.',
        ];
    }
}
