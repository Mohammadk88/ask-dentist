<?php

namespace App\Events;

use App\Models\TreatmentPlan;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlanAcceptedBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $treatmentPlan;
    public $doctor;
    public $patient;

    /**
     * Create a new event instance.
     */
    public function __construct(TreatmentPlan $treatmentPlan)
    {
        $this->treatmentPlan = $treatmentPlan;
        $this->doctor = $treatmentPlan->consultation->doctor;
        $this->patient = $treatmentPlan->consultation->patient;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->treatmentPlan->consultation_id),
            new PrivateChannel('user.' . $this->doctor->user_id),
        ];
    }

    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'plan.accepted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'treatment_plan' => [
                'id' => $this->treatmentPlan->id,
                'title' => $this->treatmentPlan->title,
                'description' => $this->treatmentPlan->description,
                'total_cost' => $this->treatmentPlan->total_cost,
                'status' => $this->treatmentPlan->status,
                'consultation_id' => $this->treatmentPlan->consultation_id,
                'accepted_at' => $this->treatmentPlan->accepted_at?->toISOString(),
            ],
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->name,
            ],
            'consultation' => [
                'id' => $this->treatmentPlan->consultation_id,
                'status' => $this->treatmentPlan->consultation->status,
            ],
        ];
    }
}