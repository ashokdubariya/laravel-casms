<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApprovalRequestRequest;
use App\Http\Requests\UpdateApprovalRequestRequest;
use App\Models\ApprovalRequest;
use App\Models\ApprovalAttachment;
use App\Models\AuditLog;
use App\Models\Client;
use App\Services\ApprovalService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function __construct(
        protected ApprovalService $approvalService,
        protected PdfService $pdfService
    ) {}

    /**
     * Display a listing of approval requests.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ApprovalRequest::class);

        $user = $request->user();
        
        // Admin and Manager can see all approvals, others see only their own
        $query = ApprovalRequest::with(['attachments', 'activeToken', 'client', 'creator']);
        
        if (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            $query->where('created_by', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $approvals = $query->latest()->paginate(15);
        $clients = Client::active()->orderBy('first_name')->get();

        // Calculate stats
        $stats = [
            'total' => ApprovalRequest::count(),
            'pending' => ApprovalRequest::where('status', 'pending')->count(),
            'approved' => ApprovalRequest::where('status', 'approved')->count(),
            'rejected' => ApprovalRequest::where('status', 'rejected')->count(),
        ];

        return view('approvals.index', compact('approvals', 'clients', 'stats'));
    }

    /**
     * Show the form for creating a new approval request.
     */
    public function create()
    {
        $this->authorize('create', ApprovalRequest::class);

        $clients = Client::active()->orderBy('first_name')->get();
        return view('approvals.create', compact('clients'));
    }

    /**
     * Store a newly created approval request.
     */
    public function store(StoreApprovalRequestRequest $request)
    {
        $approval = $this->approvalService->createApprovalRequest(
            $request->validated(),
            $request->user()->id
        );

        // Handle file uploads
        if ($request->hasFile('attachment_files')) {
            foreach ($request->file('attachment_files') as $file) {
                $path = $file->store('approval-attachments', 'public');
                
                ApprovalAttachment::create([
                    'approval_request_id' => $approval->id,
                    'type' => $this->getFileType($file),
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        AuditLog::log('approvals', 'create', $approval, 'Approval request created', null, $request->validated());

        return redirect()->route('approvals.show', $approval)
            ->with('success', 'Approval request created successfully!');
    }

    /**
     * Determine file type from mime type
     */
    private function getFileType($file): string
    {
        $mimeType = $file->getMimeType();
        
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        return 'document';
    }

    /**
     * Display the specified approval request.
     */
    public function show(ApprovalRequest $approval)
    {
        $this->authorize('view', $approval);

        $approval->load(['attachments', 'activeToken', 'history', 'client', 'creator']);

        return view('approvals.show', compact('approval'));
    }

    /**
     * Show the form for editing the specified approval request.
     */
    public function edit(ApprovalRequest $approval)
    {
        $this->authorize('update', $approval);

        if (!$approval->isPending()) {
            return redirect()->route('approvals.show', $approval)
                ->with('error', 'Cannot edit a completed approval request.');
        }

        $clients = Client::active()->orderBy('first_name')->get();
        return view('approvals.edit', compact('approval', 'clients'));
    }

    /**
     * Update the specified approval request.
     */
    public function update(UpdateApprovalRequestRequest $request, ApprovalRequest $approval)
    {
        $this->authorize('update', $approval);

        try {
            $oldValues = $approval->only(['subject', 'description', 'notes', 'status']);
            $this->approvalService->updateApprovalRequest($approval, $request->validated());

            // Handle new file uploads
            if ($request->hasFile('attachment_files')) {
                foreach ($request->file('attachment_files') as $file) {
                    $path = $file->store('approval-attachments', 'public');
                    
                    ApprovalAttachment::create([
                        'approval_request_id' => $approval->id,
                        'type' => $this->getFileType($file),
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }
            }

            AuditLog::log('approvals', 'update', $approval, 'Approval request updated', $oldValues, $request->validated());

            return redirect()->route('approvals.show', $approval)
                ->with('success', 'Approval request updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified approval request.
     */
    public function destroy(ApprovalRequest $approval)
    {
        $this->authorize('delete', $approval);

        $oldValues = $approval->toArray();
        $this->approvalService->deleteApprovalRequest($approval);

        AuditLog::log('approvals', 'delete', $approval, 'Approval request deleted', $oldValues);

        return redirect()->route('approvals.index')
            ->with('success', 'Approval request deleted successfully.');
    }

    /**
     * Add an attachment to the approval request.
     */
    public function addAttachment(Request $request, ApprovalRequest $approval)
    {
        $this->authorize('update', $approval);

        $request->validate([
            'type' => 'required|in:image,document,url',
            'file' => 'required_if:type,image,document|file',
            'url' => 'required_if:type,url|url:http,https|max:500',
        ]);

        $this->approvalService->addAttachment($approval, $request->all());

        return back()->with('success', 'Attachment added successfully!');
    }

    /**
     * Delete an attachment.
     */
    public function deleteAttachment(ApprovalRequest $approval, ApprovalAttachment $attachment)
    {
        $this->authorize('update', $approval);

        if ($attachment->approval_request_id !== $approval->id) {
            abort(404);
        }

        // Delete file from storage if it exists
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $this->approvalService->deleteAttachment($attachment);

        return back()->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Download an attachment.
     */
    public function downloadAttachment(ApprovalAttachment $attachment)
    {
        $approval = $attachment->approvalRequest;
        $this->authorize('view', $approval);

        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download(
                $attachment->file_path,
                $attachment->file_name ?? 'attachment'
            );
        }

        return back()->with('error', 'File not found.');
    }

    /**
     * Send reminder to client.
     */
    public function sendReminder(ApprovalRequest $approval)
    {
        $this->authorize('sendReminder', $approval);

        if (!$approval->isPending()) {
            return back()->with('error', 'Cannot send reminder for completed approval.');
        }

        $this->approvalService->sendReminder($approval);

        return back()->with('success', 'Reminder sent successfully!');
    }

    /**
     * Regenerate approval token.
     */
    public function regenerateToken(ApprovalRequest $approval)
    {
        $this->authorize('regenerateToken', $approval);

        if (!$approval->isPending()) {
            return back()->with('error', 'Cannot regenerate token for completed approval.');
        }

        $this->approvalService->generateToken($approval);

        return back()->with('success', 'New approval link generated successfully!');
    }

    /**
     * Download approval proof as PDF.
     */
    public function downloadPdf(ApprovalRequest $approval)
    {
        $this->authorize('downloadPdf', $approval);

        return $this->pdfService->downloadApprovalProof($approval);
    }

    /**
     * View approval history.
     */
    public function history(ApprovalRequest $approval)
    {
        $this->authorize('viewHistory', $approval);

        $approval->load('history');

        return view('approvals.history', compact('approval'));
    }
}
