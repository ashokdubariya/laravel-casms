<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRejectRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'Please provide a reason for rejecting this approval.',
            'comment.min' => 'Please provide at least 10 characters explaining your rejection.',
            'comment.max' => 'Rejection reason must not exceed 1000 characters.',
        ];
    }
}
