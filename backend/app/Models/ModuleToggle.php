<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleToggle extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'enabled',
        'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    // Static helper methods
    public static function isEnabled(string $key): bool
    {
        return static::where('key', $key)->where('enabled', true)->exists();
    }

    public static function toggle(string $key): bool
    {
        $module = static::where('key', $key)->first();
        if ($module) {
            $module->enabled = !$module->enabled;
            $module->save();
            return $module->enabled;
        }
        return false;
    }
}
