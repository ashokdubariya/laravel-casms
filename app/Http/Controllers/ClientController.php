<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AuditLog;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Client Management Controller
 * 
 * Handles CRUD operations for client management.
 * Protected by permission middleware in routes.
 */
class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $query = Client::with(['creator'])
            ->withCount('approvalRequests');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $clients = $query->paginate(15)->withQueryString();
        
        // Get statistics
        $stats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'inactive' => Client::where('status', 'inactive')->count(),
            'with_approvals' => Client::has('approvalRequests')->count(),
        ];

        return view('clients.index', compact('clients', 'stats'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(ClientRequest $request)
    {
        $validated = $request->validated();

        $validated['created_by'] = Auth::id();
        $client = Client::create($validated);

        AuditLog::log('clients', 'create', $client, 'Client created', null, $validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client created successfully!');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client->load([
            'approvalRequests' => function($query) {
                $query->latest()->with('creator')->limit(10);
            },
            'creator'
        ]);

        // Get approval statistics
        $stats = [
            'total' => $client->approvalRequests()->count(),
            'pending' => $client->approvalRequests()->where('status', 'pending')->count(),
            'approved' => $client->approvalRequests()->where('status', 'approved')->count(),
            'rejected' => $client->approvalRequests()->where('status', 'rejected')->count(),
        ];

        return view('clients.show', compact('client', 'stats'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $validated = $request->validated();
        $oldValues = $client->only(array_keys($validated));

        $validated['updated_by'] = Auth::id();
        $client->update($validated);

        AuditLog::log('clients', 'update', $client, 'Client updated', $oldValues, $validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified client from storage (soft delete).
     */
    public function destroy(Client $client)
    {
        // Check if client has approval requests
        if ($client->approvalRequests()->exists()) {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Cannot delete client with existing approval requests. Please archive the client instead.');
        }

        $oldValues = $client->toArray();
        $client->delete();

        AuditLog::log('clients', 'delete', $client, 'Client deleted', $oldValues);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    /**
     * Toggle client status (active/inactive).
     */
    public function toggleStatus(Client $client)
    {
        $newStatus = $client->status === 'active' ? 'inactive' : 'active';
        $client->update([
            'status' => $newStatus,
            'updated_by' => Auth::id(),
        ]);

        $message = $client->status === 'active' 
            ? 'Client activated successfully!' 
            : 'Client deactivated successfully!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $client->status,
        ]);
    }

    /**
     * Get client data for AJAX requests (for dropdowns, etc.)
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $clients = Client::active()
            ->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function($client) {
                return [
                    'id' => $client->id,
                    'text' => $client->full_name . ' (' . $client->email . ')',
                    'email' => $client->email,
                    'company' => $client->company_name,
                ];
            });

        return response()->json($clients);
    }
}
