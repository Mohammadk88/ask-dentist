<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhoneVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // In production, you would use SMS channels like 'twilio', 'nexmo', etc.
        // For now, we'll use the database channel for logging
        return ['database'];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * Note: This would be implemented with your SMS provider (Twilio, Nexmo, etc.)
     */
    public function toSms(object $notifiable): string
    {
        return "Your Ask Dentist verification code is: {$this->code}. Valid for 10 minutes.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'phone_verification',
            'code' => $this->code,
            'phone' => $notifiable->phone,
            'message' => "Your Ask Dentist verification code is: {$this->code}. Valid for 10 minutes.",
        ];
    }
}
