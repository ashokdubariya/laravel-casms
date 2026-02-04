<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRejectRequest;
use App\Models\ApprovalToken;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ClientApprovalController extends Controller
{
    public function __construct(
        protected ApprovalService $approvalService
    ) {}

    /**
     * Show the approval review page to the client.
     */
    public function review(Request $request)
    {
        $token = $request->approval_token;
        $approval = $token->approvalRequest;
        $approval->load('attachments');

        return view('client.review', compact('approval', 'token'));
    }

    /**
     * Client approves the request.
     */
    public function approve(Request $request)
    {
        $token = $request->approval_token;

        $this->approvalService->approveByClient($token);

        return redirect()->route('approval.success')
            ->with('success', 'Thank you! Your approval has been recorded.');
    }

    /**
     * Client rejects the request.
     */
    public function reject(ClientRejectRequest $request)
    {
        $token = $request->approval_token;

        $this->approvalService->rejectByClient($token, $request->comment);

        return redirect()->route('approval.success')
            ->with('success', 'Thank you for your feedback. Your rejection has been recorded.');
    }

    /**
     * Show expired token page.
     */
    public function expired()
    {
        return view('client.expired');
    }

    /**
     * Show already responded page.
     */
    public function alreadyResponded()
    {
        return view('client.already-responded');
    }

    /**
     * Show success page after approval/rejection.
     */
    public function success()
    {
        return view('client.success');
    }
}
