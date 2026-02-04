<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApprovalRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $approval = $this->route('approval');
        
        // Cannot edit approved or rejected requests (immutable for audit trail)
        if (in_array($approval->status, ['approved', 'rejected'])) {
            abort(403, 'Cannot edit approved or rejected approval requests. They are locked for audit purposes.');
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'version' => 'nullable|string|max:50',
            'client_id' => 'sometimes|required|exists:clients,id',
            'priority' => 'sometimes|required|in:low,medium,high',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'internal_notes' => 'nullable|string|max:5000',
            'due_date' => 'nullable|date',
            'message' => 'nullable|string|max:5000',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for this approval request.',
        ];
    }
}
