<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Try to get admin user, but don't fail if none exists
        // This allows email templates to be seeded before creating admin
        $admin = User::where('role_id', 1)->first();
        $createdBy = $admin ? $admin->id : null;

        $templates = [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome-email',
                'subject' => 'Welcome to {{app_name}}!',
                'body_html' => $this->getWelcomeEmailHtml(),
                'body_text' => $this->getWelcomeEmailText(),
                'type' => 'notification',
                'variables' => ['user_name', 'app_name', 'login_url'],
                'description' => 'Sent to new users when their account is created',
                'status' => 'active',
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Approval Request Notification',
                'slug' => 'approval-request',
                'subject' => 'New Approval Request: {{title}}',
                'body_html' => $this->getApprovalRequestHtml(),
                'body_text' => $this->getApprovalRequestText(),
                'type' => 'approval',
                'variables' => ['client_name', 'title', 'description', 'approval_url', 'due_date'],
                'description' => 'Sent to clients when a new approval request is created',
                'status' => 'active',
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Approval Approved',
                'slug' => 'approval-approved',
                'subject' => 'Approval Request Approved: {{title}}',
                'body_html' => $this->getApprovalApprovedHtml(),
                'body_text' => $this->getApprovalApprovedText(),
                'type' => 'approval',
                'variables' => ['client_name', 'title', 'approved_at', 'team_member'],
                'description' => 'Sent to team when client approves a request',
                'status' => 'active',
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Approval Rejected',
                'slug' => 'approval-rejected',
                'subject' => 'Approval Request Rejected: {{title}}',
                'body_html' => $this->getApprovalRejectedHtml(),
                'body_text' => $this->getApprovalRejectedText(),
                'type' => 'approval',
                'variables' => ['client_name', 'title', 'rejection_reason', 'team_member'],
                'description' => 'Sent to team when client rejects a request',
                'status' => 'active',
                'created_by' => $createdBy,
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset Your Password',
                'body_html' => $this->getPasswordResetHtml(),
                'body_text' => $this->getPasswordResetText(),
                'type' => 'system',
                'variables' => ['user_name', 'reset_url', 'app_name'],
                'description' => 'Sent when user requests password reset',
                'status' => 'active',
                'created_by' => $createdBy,
            ],
        ];

        foreach ($templates as $template) {
            // Use updateOrCreate for idempotency
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']], // Find by slug
                $template // Update/create with all data
            );
        }

        $this->command->info('Created/updated 5 premium email templates');
    }

    private function getWelcomeEmailHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #0F172A; margin: 0; padding: 0; background-color: #F8FAFC; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #1a425f; color: white; padding: 40px 30px; text-align: center; }
        .logo { width: 60px; height: 60px; background: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .logo-icon { font-size: 32px; color: #1a425f; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: #1a425f; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .button:hover { background: #143752; }
        .footer { background: #F8FAFC; padding: 30px; text-align: center; color: #64748b; font-size: 14px; border-top: 1px solid #CBD5E1; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; }
        p { margin: 16px 0; color: #475569; }
        .highlight { background: #85c34e; color: white; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{app_name}}!</h1>
        </div>
        <div class="content">
            <p>Hi <strong>{{user_name}}</strong>,</p>
            <p>We're excited to have you on board! Your account has been successfully created and you're ready to start managing client approvals efficiently.</p>
            <p>With {{app_name}}, you can:</p>
            <ul style="color: #475569; line-height: 1.8;">
                <li>Create and track approval requests</li>
                <li>Manage clients and projects</li>
                <li>Collaborate with your team</li>
                <li>Get real-time notifications</li>
            </ul>
            <center>
                <a href="{{login_url}}" class="button">Login to Your Account</a>
            </center>
            <p>If you have any questions, our support team is always here to help.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 {{app_name}}. All rights reserved.</p>
            <p>This email was sent because an account was created for this email address.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getWelcomeEmailText(): string
    {
        return <<<'TEXT'
Welcome to {{app_name}}!

Hi {{user_name}},

We're excited to have you on board! Your account has been successfully created and you're ready to start managing client approvals efficiently.

With {{app_name}}, you can:
- Create and track approval requests
- Manage clients and projects
- Collaborate with your team
- Get real-time notifications

Login to your account: {{login_url}}

If you have any questions, our support team is always here to help.

&copy; 2026 {{app_name}}. All rights reserved.
TEXT;
    }

    private function getApprovalRequestHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #0F172A; margin: 0; padding: 0; background-color: #F8FAFC; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #1a425f; color: white; padding: 40px 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background: #F8FAFC; border-left: 4px solid #1a425f; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .button { display: inline-block; background: #85c34e; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .button:hover { background: #72a942; }
        .footer { background: #F8FAFC; padding: 30px; text-align: center; color: #64748b; font-size: 14px; border-top: 1px solid #CBD5E1; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; }
        h2 { color: #1a425f; font-size: 20px; margin: 0 0 10px 0; }
        p { margin: 16px 0; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Approval Request</h1>
        </div>
        <div class="content">
            <p>Hi <strong>{{client_name}}</strong>,</p>
            <p>You have received a new approval request that requires your review.</p>
            
            <div class="info-box">
                <h2>{{title}}</h2>
                <p style="margin: 10px 0; color: #475569;">{{description}}</p>
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #64748b;"><strong>Due Date:</strong> {{due_date}}</p>
            </div>

            <p>Please review the request and provide your approval or feedback at your earliest convenience.</p>

            <center>
                <a href="{{approval_url}}" class="button">Review & Approve</a>
            </center>

            <p style="font-size: 14px; color: #64748b; margin-top: 30px;">This link will remain active until you respond to this approval request.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Client Approval System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getApprovalRequestText(): string
    {
        return <<<'TEXT'
New Approval Request

Hi {{client_name}},

You have received a new approval request that requires your review.

{{title}}
{{description}}

Due Date: {{due_date}}

Please review the request and provide your approval or feedback at your earliest convenience.

Review & Approve: {{approval_url}}

This link will remain active until you respond to this approval request.

&copy; 2026 Client Approval System. All rights reserved.
TEXT;
    }

    private function getApprovalApprovedHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #0F172A; margin: 0; padding: 0; background-color: #F8FAFC; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #85c34e; color: white; padding: 40px 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .success-icon { font-size: 64px; margin-bottom: 20px; }
        .info-box { background: #F8FAFC; border-left: 4px solid #85c34e; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { background: #F8FAFC; padding: 30px; text-align: center; color: #64748b; font-size: 14px; border-top: 1px solid #CBD5E1; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; }
        p { margin: 16px 0; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Approval Received!</h1>
        </div>
        <div class="content">
            <p>Great news, <strong>{{team_member}}</strong>!</p>
            <p><strong>{{client_name}}</strong> has approved your request.</p>
            
            <div class="info-box">
                <h2 style="color: #85c34e; font-size: 20px; margin: 0 0 10px 0;">{{title}}</h2>
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #64748b;"><strong>Approved at:</strong> {{approved_at}}</p>
            </div>

            <p>You can now proceed with the next steps for this project. The client has been notified of their approval.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Client Approval System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getApprovalApprovedText(): string
    {
        return <<<'TEXT'
Approval Received!

Great news, {{team_member}}!

{{client_name}} has approved your request.

{{title}}
Approved at: {{approved_at}}

You can now proceed with the next steps for this project. The client has been notified of their approval.

&copy; 2026 Client Approval System. All rights reserved.
TEXT;
    }

    private function getApprovalRejectedHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #0F172A; margin: 0; padding: 0; background-color: #F8FAFC; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #ef4444; color: white; padding: 40px 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background: #FEF2F2; border-left: 4px solid #ef4444; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { background: #F8FAFC; padding: 30px; text-align: center; color: #64748b; font-size: 14px; border-top: 1px solid #CBD5E1; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; }
        p { margin: 16px 0; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Request Needs Revision</h1>
        </div>
        <div class="content">
            <p>Hi <strong>{{team_member}}</strong>,</p>
            <p><strong>{{client_name}}</strong> has requested changes to the following approval request.</p>
            
            <div class="info-box">
                <h2 style="color: #ef4444; font-size: 20px; margin: 0 0 10px 0;">{{title}}</h2>
                <p style="margin: 15px 0 0 0;"><strong>Client Feedback:</strong></p>
                <p style="margin: 10px 0; color: #991b1b;">{{rejection_reason}}</p>
            </div>

            <p>Please review the feedback and make the necessary adjustments before resubmitting for approval.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Client Approval System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getApprovalRejectedText(): string
    {
        return <<<'TEXT'
Request Needs Revision

Hi {{team_member}},

{{client_name}} has requested changes to the following approval request.

{{title}}

Client Feedback:
{{rejection_reason}}

Please review the feedback and make the necessary adjustments before resubmitting for approval.

&copy; 2026 Client Approval System. All rights reserved.
TEXT;
    }

    private function getPasswordResetHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #0F172A; margin: 0; padding: 0; background-color: #F8FAFC; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #1a425f; color: white; padding: 40px 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .warning-box { background: #FEF9C3; border-left: 4px solid #EAB308; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .button { display: inline-block; background: #1a425f; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background: #F8FAFC; padding: 30px; text-align: center; color: #64748b; font-size: 14px; border-top: 1px solid #CBD5E1; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; }
        p { margin: 16px 0; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        <div class="content">
            <p>Hi <strong>{{user_name}}</strong>,</p>
            <p>We received a request to reset your password for your {{app_name}} account.</p>
            
            <center>
                <a href="{{reset_url}}" class="button">Reset Password</a>
            </center>

            <div class="warning-box">
                <p style="margin: 0; color: #854D0E;"><strong>Security Notice:</strong> This link will expire in 60 minutes. If you didn't request this password reset, please ignore this email.</p>
            </div>

            <p style="font-size: 14px; color: #64748b;">If the button doesn't work, copy and paste this URL into your browser:</p>
            <p style="font-size: 12px; color: #94a3b8; word-break: break-all;">{{reset_url}}</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 {{app_name}}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getPasswordResetText(): string
    {
        return <<<'TEXT'
Reset Your Password

Hi {{user_name}},

We received a request to reset your password for your {{app_name}} account.

Click the link below to reset your password:
{{reset_url}}

Security Notice: This link will expire in 60 minutes. If you didn't request this password reset, please ignore this email.

&copy; 2026 {{app_name}}. All rights reserved.
TEXT;
    }
}
