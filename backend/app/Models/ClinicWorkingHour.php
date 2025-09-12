<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ClinicWorkingHour extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'break_start',
        'break_end',
        'is_closed',
        'notes',
    ];

    protected $casts = [
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
        'is_closed' => 'boolean',
    ];

    // Relationships
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeForDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Helper methods
    public function getDayDisplayAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }

    public function getFormattedHoursAttribute(): string
    {
        if ($this->is_closed) {
            return 'Closed';
        }

        $hours = "{$this->opening_time->format('H:i')} - {$this->closing_time->format('H:i')}";
        
        if ($this->break_start && $this->break_end) {
            $hours .= " (Break: {$this->break_start->format('H:i')} - {$this->break_end->format('H:i')})";
        }
        
        return $hours;
    }
}
