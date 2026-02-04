<?php

namespace Database\Seeders;

use App\Models\ApprovalRequest;
use App\Models\ApprovalToken;
use App\Models\ApprovalHistory;
use App\Models\ApprovalAttachment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApprovalRequestSeeder extends Seeder
{
    /**
     * Seed realistic approval requests for the 25 clients.
     * 
     * IDEMPOTENT: Only creates approvals if they don't exist for the client
     */
    public function run(): void
    {
        $users = User::all();
        $clients = Client::all();
        
        if ($users->isEmpty() || $clients->isEmpty()) {
            $this->command->warn('Please run UserSeeder and ClientSeeder first.');
            return;
        }

        $statuses = ['pending', 'approved', 'rejected', 'pending', 'approved']; // Weighted for more pending/approved
        $priorities = ['low', 'medium', 'high', 'medium', 'medium']; // Weighted for more medium priority
        
        $approvalTypes = [
            'Website Homepage Redesign' => 'Complete redesign of main landing page with modern UI/UX',
            'Brand Logo Design' => 'New company logo with variations for different mediums',
            'Marketing Campaign Materials' => 'Banner ads, social media graphics, and email templates',
            'Mobile App UI Design' => 'User interface design for iOS and Android applications',
            'Product Packaging Design' => 'Packaging design for new product line launch',
            'Corporate Brochure Layout' => 'Multi-page brochure for company services and portfolio',
            'E-commerce Product Photos' => 'Professional product photography for online store',
            'Social Media Content Calendar' => 'Monthly content strategy and graphic assets',
            'Video Production Storyboard' => 'Storyboard and creative direction for promotional video',
            'Email Newsletter Template' => 'Responsive HTML email template design',
            'Trade Show Booth Design' => 'Exhibition booth graphics and layout planning',
            'Infographic Design' => 'Data visualization infographic for annual report',
            'Menu Design' => 'Restaurant menu design for print and digital display',
            'Business Card & Stationery' => 'Corporate identity package design',
            'Landing Page Mockup' => 'High-converting landing page for product launch',
            'Mobile Game Assets' => 'UI elements and character designs for mobile game',
            'Presentation Deck Design' => 'Investor pitch deck with branded template',
            'Annual Report Layout' => 'Design and layout for company annual report',
            'Podcast Cover Art' => 'Podcast branding and episode graphics',
            'Event Invitation Design' => 'Digital and print invitations for corporate event',
            'Signage & Wayfinding' => 'Office signage and directional graphics',
            'Book Cover Design' => 'Front and back cover design for publication',
            'App Store Screenshots' => 'Marketing screenshots for app store listing',
            'Website Banner Ads' => 'Display advertising campaign materials',
            'Product Catalog Design' => 'Multi-page catalog for product showcase',
        ];

        $titles = array_keys($approvalTypes);
        $created = 0;
        
        foreach ($clients as $index => $client) {
            // IDEMPOTENCY: Skip if this client already has an approval request
            if (ApprovalRequest::where('client_id', $client->id)->exists()) {
                continue;
            }
            
            $title = $titles[$index] ?? 'General Design Approval';
            $description = $approvalTypes[$title] ?? 'Design approval request';
            $randomUser = $users->random();
            $randomStatus = $statuses[array_rand($statuses)];
            $randomPriority = $priorities[array_rand($priorities)];
            
            // Create dates relative to now
            $createdDays = rand(1, 30);
            $createdAt = now()->subDays($createdDays);
            
            $approvalData = [
                'title' => $title,
                'description' => $description,
                'message' => 'Please review and provide your approval for ' . $title,
                'client_id' => $client->id,
                'client_name' => $client->first_name . ' ' . $client->last_name,
                'client_email' => $client->email,
                'status' => $randomStatus,
                'priority' => $randomPriority,
                'due_date' => now()->addDays(rand(5, 30))->format('Y-m-d'),
                'created_by' => $randomUser->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'internal_notes' => $this->generateNotes($randomStatus),
            ];
            
            // Add approval/rejection details for completed requests
            if ($randomStatus === 'approved') {
                $approvalData['approved_at'] = $createdAt->copy()->addDays(rand(1, $createdDays));
                $approvalData['client_comment'] = 'Approved - Looks great! Proceed with implementation.';
            } elseif ($randomStatus === 'rejected') {
                $approvalData['rejected_at'] = $createdAt->copy()->addDays(rand(1, $createdDays));
                $approvalData['client_comment'] = 'Please revise color scheme and typography choices.';
            }
            
            $approval = ApprovalRequest::create($approvalData);
            
            // Create approval history (creation event)
            ApprovalHistory::create([
                'approval_request_id' => $approval->id,
                'action' => 'created',
                'performed_by' => $randomUser->name,
                'version' => $approval->version ?? 'v1',
                'metadata' => json_encode(['status' => 'pending']),
                'created_at' => $createdAt,
            ]);
            
            // Create approval token for pending/approved requests
            if (in_array($randomStatus, ['pending', 'approved'])) {
                $token = ApprovalToken::create([
                    'approval_request_id' => $approval->id,
                    'token' => ApprovalToken::generateSecureToken(),
                    'expires_at' => now()->addDays(7),
                    'used_at' => $randomStatus === 'approved' ? $approvalData['approved_at'] : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                
                // Add token generation history
                ApprovalHistory::create([
                    'approval_request_id' => $approval->id,
                    'action' => 'token_generated',
                    'performed_by' => 'system',
                    'version' => $approval->version ?? 'v1',
                    'metadata' => json_encode(['token_id' => $token->id]),
                    'created_at' => $createdAt,
                ]);
            }
            
            // Add approval/rejection history for completed requests
            if ($randomStatus === 'approved') {
                ApprovalHistory::create([
                    'approval_request_id' => $approval->id,
                    'action' => 'approved',
                    'performed_by' => 'client',
                    'version' => $approval->version ?? 'v1',
                    'comment' => $approvalData['client_comment'],
                    'metadata' => json_encode(['status_changed' => 'pending -> approved']),
                    'created_at' => $approvalData['approved_at'],
                ]);
            } elseif ($randomStatus === 'rejected') {
                ApprovalHistory::create([
                    'approval_request_id' => $approval->id,
                    'action' => 'rejected',
                    'performed_by' => 'client',
                    'version' => $approval->version ?? 'v1',
                    'comment' => $approvalData['client_comment'],
                    'metadata' => json_encode(['status_changed' => 'pending -> rejected']),
                    'created_at' => $approvalData['rejected_at'],
                ]);
            }
            
            // Add sample attachments to some approval requests (50% chance)
            if (rand(0, 1)) {
                $attachmentCount = rand(1, 3);
                for ($i = 0; $i < $attachmentCount; $i++) {
                    ApprovalAttachment::create([
                        'approval_request_id' => $approval->id,
                        'type' => rand(0, 1) ? 'image' : 'document',
                        'file_path' => 'sample-attachments/sample-' . rand(1, 10) . '.pdf',
                        'file_name' => 'Sample Document ' . ($i + 1) . '.pdf',
                        'file_size' => rand(50000, 500000),
                        'mime_type' => 'application/pdf',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
            
            $created++;
        }

        $this->command->info("Created $created approval requests with tokens, history, and attachments");
    }
    
    private function generateNotes(string $status): string
    {
        $noteTemplates = [
            'pending' => [
                'Awaiting client review - initial submission',
                'Client requested to review by end of week',
                'First draft submitted - pending feedback',
                'Ready for client approval',
            ],
            'approved' => [
                'Client approved with minor feedback incorporated',
                'Approved for production - moving forward',
                'Client satisfied with final deliverable',
            ],
            'rejected' => [
                'Client requested revisions to color palette',
                'Needs adjustment to match brand guidelines',
                'Typography changes requested',
            ],
        ];
        
        $templates = $noteTemplates[$status] ?? ['Standard approval request'];
        return $templates[array_rand($templates)];
    }
}
