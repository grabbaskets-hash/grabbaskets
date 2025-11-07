<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DeliveryPartnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $message;
    protected $type;
    protected $actionUrl;
    protected $actionText;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $type = 'info', ?string $actionUrl = null, ?string $actionText = null, array $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type; // 'info', 'success', 'warning', 'danger'
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText ?? 'View Details';
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Add mail channel if specified in data
        if (isset($this->data['send_email']) && $this->data['send_email']) {
            $channels[] = 'mail';
        }
        
        // Add SMS channel if phone number exists and SMS enabled
        if ($notifiable->phone && config('services.sms.enabled', false)) {
            // $channels[] = 'vonage'; // or 'twilio' depending on your SMS provider
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->greeting("Hello {$notifiable->name}!")
            ->line($this->message);
        
        if ($this->actionUrl) {
            $mailMessage->action($this->actionText, $this->actionUrl);
        }
        
        return $mailMessage->line('Thank you for being a delivery partner with ' . config('app.name') . '!');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
            'action_text' => $this->actionText,
            'data' => $this->data,
            'read_at' => null,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
