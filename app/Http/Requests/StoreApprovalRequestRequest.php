<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalRequestRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'version' => 'nullable|string|max:50|regex:/^v\d+(\.\d+)*$/',
            'client_id' => 'required|exists:clients,id',
            'internal_notes' => 'nullable|string|max:5000',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'message' => 'nullable|string|max:5000',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            
            // File upload validation with security hardening
            'attachment_files' => 'nullable|array|max:10',
            'attachment_files.*' => [
                'file',
                'max:10240', // 10MB max
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt',
                'mimetypes:image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
            ],
            
            // Legacy attachments array support (if needed)
            'attachments' => 'nullable|array|max:10',
            'attachments.*.type' => 'required|in:image,document,url',
            'attachments.*.file' => 'required_if:attachments.*.type,image,document|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'attachments.*.url' => [
                'required_if:attachments.*.type,url',
                'url:http,https',
                'max:500',
                function ($attribute, $value, $fail) {
                    // Prevent SSRF - block internal IPs
                    $host = parse_url($value, PHP_URL_HOST);
                    if ($host && filter_var($host, FILTER_VALIDATE_IP)) {
                        $isPrivate = !filter_var(
                            $host,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        );
                        if ($isPrivate) {
                            $fail('Internal or private IP addresses are not allowed.');
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for this approval request.',
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client does not exist.',
            'version.regex' => 'Version must follow the format: v1, v2, v2.1, etc.',
            'priority.in' => 'Priority must be low, medium, or high.',
            
            // File upload messages
            'attachment_files.max' => 'You can attach a maximum of 10 files.',
            'attachment_files.*.max' => 'Each file must not exceed 10MB.',
            'attachment_files.*.mimes' => 'Only JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, and TXT files are allowed.',
            'attachment_files.*.mimetypes' => 'Invalid file type detected.',
            
            'attachments.max' => 'You can attach a maximum of 10 items.',
            'attachments.*.url' => 'Please provide a valid URL starting with http:// or https://',
        ];
    }
}
