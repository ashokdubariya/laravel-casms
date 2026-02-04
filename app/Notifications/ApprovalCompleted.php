<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalCompleted extends Notification implements ShouldQueue
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
        public string $status,
        public ?string $comment = null
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
        $message = (new MailMessage)
            ->subject('Client Response: ' . $this->approval->title);

        if ($this->status === 'approved') {
            $message->greeting('Good News!')
                ->line('Your approval request has been **approved** by the client:')
                ->line('**' . $this->approval->title . '**')
                ->line('Client: ' . $this->approval->client_name);
        } else {
            $message->greeting('Client Feedback Received')
                ->line('Your approval request requires changes:')
                ->line('**' . $this->approval->title . '**')
                ->line('Client: ' . $this->approval->client_name);

            if ($this->comment) {
                $message->line('**Feedback:**')
                    ->line($this->comment);
            }
        }

        return $message->action('View Details', route('approvals.show', $this->approval))
            ->line('Thank you for using our approval system!');
    }
}
