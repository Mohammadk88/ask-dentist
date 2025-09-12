<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VerificationToken extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'token',
        'contact',
        'expires_at',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the verification token
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if token is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Mark token as verified
     */
    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }

    /**
     * Increment verification attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Generate email verification token
     */
    public static function generateEmailToken(User $user): self
    {
        // Remove existing email tokens
        self::where('user_id', $user->id)
            ->where('type', 'email')
            ->delete();

        return self::create([
            'user_id' => $user->id,
            'type' => 'email',
            'token' => Str::uuid(),
            'contact' => $user->email,
            'expires_at' => now()->addHours(24),
        ]);
    }

    /**
     * Generate phone verification token
     */
    public static function generatePhoneToken(User $user): self
    {
        // Remove existing phone tokens
        self::where('user_id', $user->id)
            ->where('type', 'phone')
            ->delete();

        return self::create([
            'user_id' => $user->id,
            'type' => 'phone',
            'token' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'contact' => $user->phone,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Find valid token for verification
     */
    public static function findValidToken(string $token, string $type, string $contact): ?self
    {
        return self::where('token', $token)
            ->where('type', $type)
            ->where('contact', $contact)
            ->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->where('attempts', '<', 5)
            ->first();
    }
}
