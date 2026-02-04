<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of email templates.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $templates = $query->latest()->paginate(15)->withQueryString();

        return view('email-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new email template.
     */
    public function create()
    {
        return view('email-templates.create');
    }

    /**
     * Store a newly created email template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'type' => 'required|in:notification,approval,system',
            'description' => 'nullable|string',
            'variables' => 'nullable|json',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();

        $template = EmailTemplate::create($validated);

        AuditLog::log('email_templates', 'create', $template, null, null, $validated);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template created successfully!');
    }

    /**
     * Display the specified email template.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        AuditLog::log('email_templates', 'view', $emailTemplate);
        return view('email-templates.show', compact('emailTemplate'));
    }

    /**
     * Show the form for editing the specified email template.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-templates.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified email template.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $emailTemplate->id,
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'type' => 'required|in:notification,approval,system',
            'description' => 'nullable|string',
            'variables' => 'nullable|json',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $emailTemplate->toArray();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $emailTemplate->update($validated);

        AuditLog::log('email_templates', 'update', $emailTemplate, null, $oldValues, $validated);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template updated successfully!');
    }

    /**
     * Remove the specified email template.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        $oldValues = $emailTemplate->toArray();
        $emailTemplate->delete();

        AuditLog::log('email_templates', 'delete', $emailTemplate, null, $oldValues);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template deleted successfully!');
    }
}
