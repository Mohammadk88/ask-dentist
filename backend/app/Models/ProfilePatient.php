<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProfilePatient extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'profiles_patient';

    protected $fillable = [
        'user_id',
        'dob',
        'gender',
        'address_json',
        'medical_conditions_json',
    ];

    protected $casts = [
        'dob' => 'date',
        'address_json' => 'array',
        'medical_conditions_json' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['dob', 'gender', 'address_json'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
