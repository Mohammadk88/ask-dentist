<?php

namespace App\Notifications;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TreatmentPlanAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly TreatmentPlan $treatmentPlan
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Treatment Plan Has Been Accepted!')
            ->greeting('Congratulations!')
            ->line('Your treatment plan has been accepted by the patient.')
            ->line("Plan: {$this->treatmentPlan->title}")
            ->line("Total Cost: {$this->treatmentPlan->total_cost} {$this->treatmentPlan->currency}")
            ->line("Duration: {$this->treatmentPlan->estimated_duration_days} days")
            ->line('You can now proceed with scheduling appointments.')
            ->action('View Treatment Plan', url("/treatment-plans/{$this->treatmentPlan->id}"))
            ->line('Thank you for using our platform!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'treatment_plan_accepted',
            'treatment_plan_id' => $this->treatmentPlan->id,
            'treatment_request_id' => $this->treatmentPlan->treatment_request_id,
            'title' => $this->treatmentPlan->title,
            'total_cost' => $this->treatmentPlan->total_cost,
            'currency' => $this->treatmentPlan->currency,
            'message' => 'Your treatment plan has been accepted by the patient.',
        ];
    }
}