<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorable_type',
        'favorable_id',
    ];

    /**
     * Get the user that owns the favorite
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorited model (doctor, clinic, etc.)
     */
    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get favorites for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get favorites of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('favorable_type', $type);
    }
}