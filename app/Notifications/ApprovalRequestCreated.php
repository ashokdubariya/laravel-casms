<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use App\Models\ApprovalToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalRequestCreated extends Notification implements ShouldQueue
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
            ->subject('Approval Required: ' . $this->approval->title)
            ->greeting('Hello ' . $this->approval->client_name . ',')
            ->line('You have received a new approval request:')
            ->line('**' . $this->approval->title . '**')
            ->when($this->approval->description, function ($mail) {
                return $mail->line($this->approval->description);
            })
            ->action('Review & Respond', $this->token->approval_link)
            ->line('This link will expire ' . $this->token->expires_at->diffForHumans() . '.')
            ->line('Thank you for your timely response!');
    }
}
