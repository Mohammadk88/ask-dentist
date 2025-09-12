<?php

namespace App\Notifications;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TreatmentPlanCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly TreatmentPlan $treatmentPlan,
        public readonly string $reason = 'Another treatment plan was selected'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Treatment Plan Cancelled')
            ->greeting('Hello!')
            ->line('Your treatment plan has been cancelled.')
            ->line("Plan: {$this->treatmentPlan->title}")
            ->line("Reason: {$this->reason}")
            ->line('Don\'t worry - there are many other opportunities on our platform.')
            ->action('View Other Requests', url('/treatment-requests'))
            ->line('Thank you for using our platform!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'treatment_plan_cancelled',
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_request_id' => $this->treatmentPlan->treatment_request_id,
            'title' => $this->treatmentPlan->title,
            'reason' => $this->reason,
            'message' => "Your treatment plan '{$this->treatmentPlan->title}' has been cancelled.",
        ];
    }
}