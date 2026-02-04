<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with approval statistics.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if user can view all data (admin/manager)
        $canViewAll = $user->hasRole('admin') || $user->hasRole('manager');

        // Get statistics based on user permissions
        if ($canViewAll) {
            // Admin/Manager view - see all data
            $stats = [
                'total_clients' => Client::count(),
                'active_clients' => Client::active()->count(),
                'total_approvals' => ApprovalRequest::count(),
                'pending_approvals' => ApprovalRequest::pending()->count(),
                'approved_today' => ApprovalRequest::approved()
                    ->whereDate('updated_at', today())
                    ->count(),
                'total_users' => User::where('status', 'active')->count(),
            ];

            // Recent activity
            $recentApprovals = ApprovalRequest::with(['client', 'creator', 'attachments'])
                ->latest()
                ->limit(10)
                ->get();

            $recentClients = Client::latest()
                ->limit(5)
                ->get();
        } else {
            // Regular user view - see only own data
            $stats = [
                'total' => ApprovalRequest::where('created_by', $user->id)->count(),
                'pending' => ApprovalRequest::where('created_by', $user->id)->pending()->count(),
                'approved' => ApprovalRequest::where('created_by', $user->id)->approved()->count(),
                'rejected' => ApprovalRequest::where('created_by', $user->id)->rejected()->count(),
            ];

            $recentApprovals = ApprovalRequest::where('created_by', $user->id)
                ->with(['client', 'attachments'])
                ->latest()
                ->limit(10)
                ->get();

            $recentClients = collect();
        }

        // Status breakdown for chart
        $statusBreakdown = ApprovalRequest::select('status', DB::raw('count(*) as count'))
            ->when(!$canViewAll, function($query) use ($user) {
                return $query->where('created_by', $user->id);
            })
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact('stats', 'recentApprovals', 'recentClients', 'statusBreakdown', 'canViewAll'));
    }
}
