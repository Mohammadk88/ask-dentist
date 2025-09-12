<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentRequestDoctor extends Model
{
    protected $fillable = [
        'treatment_request_id',
        'doctor_id',
        'dispatch_order',
        'dispatch_score',
        'status',
        'notified_at',
        'responded_at',
        'decline_reason',
    ];

    protected $casts = [
        'dispatch_score' => 'float',
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    // Relationships
    public function treatmentRequest(): BelongsTo
    {
        return $this->belongsTo(TreatmentRequest::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopeByDispatchOrder($query)
    {
        return $query->orderBy('dispatch_order');
    }

    // Methods
    public function markAsNotified(): void
    {
        $this->update(['notified_at' => now()]);
    }

    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);
    }

    public function decline(string $reason = null): void
    {
        $this->update([
            'status' => 'declined',
            'responded_at' => now(),
            'decline_reason' => $reason,
        ]);
    }
}