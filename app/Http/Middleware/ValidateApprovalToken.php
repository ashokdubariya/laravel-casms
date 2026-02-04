<?php

namespace App\Http\Middleware;

use App\Models\ApprovalToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApprovalToken
{
    /**
     * Validate that the approval token is valid, not expired, and not used.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenValue = $request->route('token');

        if (!$tokenValue) {
            abort(404, 'Invalid approval link.');
        }

        $token = ApprovalToken::where('token', $tokenValue)->first();

        if (!$token) {
            abort(404, 'Invalid approval link.');
        }

        if ($token->isExpired()) {
            return redirect()->route('approval.expired')
                ->with('error', 'This approval link has expired.');
        }

        if ($token->isUsed()) {
            return redirect()->route('approval.already-responded')
                ->with('info', 'You have already responded to this approval request.');
        }

        if (!$token->approvalRequest->isPending()) {
            return redirect()->route('approval.already-responded')
                ->with('info', 'This approval request has already been completed.');
        }

        // Store token in request for controller access
        $request->merge(['approval_token' => $token]);

        return $next($request);
    }
}
