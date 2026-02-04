<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use App\Models\ApprovalToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalReminder extends Notification implements ShouldQueue
{
    use Queueable;
    
    /**
     * Number of times to attempt notification delivery
     */
    public $tries = 3;
    
    /**
     * Timeout for notification job
     */
    public $timeout = 30;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ApprovalRequest $approval,
        public ApprovalToken $token
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Approval Required - ' . $this->approval->title)
            ->greeting('Hello ' . $this->approval->client_name . ',')
            ->line('This is a friendly reminder about a pending approval request:')
            ->line('**' . $this->approval->title . '**')
            ->action('Review & Respond', $this->token->approval_link)
            ->line('This link will expire ' . $this->token->expires_at->diffForHumans() . '.')
            ->line('We appreciate your prompt attention to this matter.');
    }
}
