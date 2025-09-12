<?php

namespace App\Models;

use App\Events\MessageSent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Message extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'from_user_id',
        'to_user_id', 
        'request_id',
        'body',
        'type',
        'attachment_path',
        'attachments_json',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachments_json' => 'array',
    ];

    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';
    const TYPE_SYSTEM = 'system';

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Model boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            if (!$message->type) {
                $message->type = self::TYPE_TEXT;
            }
        });

        static::created(function ($message) {
            // Broadcast the message after it's created
            broadcast(new MessageSent($message, $message->user))->toOthers();
        });
    }

    /**
     * Get the treatment request that owns this message
     */
    public function treatmentRequest()
    {
        return $this->belongsTo(TreatmentRequest::class, 'request_id');
    }

    /**
     * Get the user who sent this message
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who sent this message (alias)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who receives this message
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Legacy consultation relationship for backwards compatibility
     */
    public function consultation()
    {
        return $this->treatmentRequest();
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Get message content
     */
    public function getContentAttribute()
    {
        return $this->body;
    }

    /**
     * Set message content
     */
    public function setContentAttribute($value)
    {
        $this->body = $value;
    }

    /**
     * Get user_id for compatibility
     */
    public function getUserIdAttribute()
    {
        return $this->from_user_id;
    }

    /**
     * Set user_id for compatibility
     */
    public function setUserIdAttribute($value)
    {
        $this->from_user_id = $value;
    }

    /**
     * Get consultation_id for compatibility
     */
    public function getConsultationIdAttribute()
    {
        return $this->request_id;
    }

    /**
     * Set consultation_id for compatibility
     */
    public function setConsultationIdAttribute($value)
    {
        $this->request_id = $value;
    }
}
