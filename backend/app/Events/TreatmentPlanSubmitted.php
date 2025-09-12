<?php

namespace App\Events;

use App\Infrastructure\Models\TreatmentPlan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TreatmentPlanSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TreatmentPlan $treatmentPlan;

    /**
     * Create a new event instance.
     */
    public function __construct(TreatmentPlan $treatmentPlan)
    {
        $this->treatmentPlan = $treatmentPlan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('treatment-plan.' . $this->treatmentPlan->id),
        ];
    }
}
