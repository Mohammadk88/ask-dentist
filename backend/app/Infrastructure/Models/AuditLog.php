<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Infrastructure Model: AuditLog
 *
 * This model represents the persistence layer for audit log entities
 * following hexagonal architecture principles.
 */
class AuditLog extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false; // We only use created_at

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'metadata',
        'request_method',
        'request_url',
        'session_id',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, string $modelType, string $modelId = null)
    {
        $query = $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    public function scopeByIpAddress($query, string $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeSensitiveActions($query)
    {
        return $query->whereIn('action', [
            'login',
            'logout',
            'password_changed',
            'email_changed',
            'role_changed',
            'created',
            'updated',
            'deleted',
            'treatment_plan_accepted',
            'treatment_plan_rejected',
            'appointment_scheduled',
            'appointment_cancelled',
        ]);
    }

    // Accessors
    public function getIsSensitiveAttribute(): bool
    {
        return in_array($this->action, [
            'login',
            'logout',
            'password_changed',
            'email_changed',
            'role_changed',
            'created',
            'updated',
            'deleted',
            'treatment_plan_accepted',
            'treatment_plan_rejected',
            'appointment_scheduled',
            'appointment_cancelled',
        ]);
    }

    public function getModelNameAttribute(): ?string
    {
        if (!$this->model_type) {
            return null;
        }

        return class_basename($this->model_type);
    }

    // Static helper methods
    public static function log(
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = []
    ): self {
        $user = auth()->user();
        $request = request();

        return static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent() ?? 'Unknown',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'session_id' => $request->session()?->getId(),
            'created_at' => now(),
        ]);
    }
}
