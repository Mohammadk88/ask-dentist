<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Infrastructure Model: TeethReference
 *
 * This model represents the persistence layer for FDI teeth reference data
 * following hexagonal architecture principles.
 */
class TeethReference extends Model
{
    use HasFactory;

    protected $table = 'teeth_reference';

    protected $fillable = [
        'fdi_code',
        'name',
        'type',
        'quadrant',
        'position_in_quadrant',
        'is_permanent',
        'description',
    ];

    protected $casts = [
        'position_in_quadrant' => 'integer',
        'is_permanent' => 'boolean',
    ];

    // Scopes
    public function scopeByQuadrant($query, string $quadrant)
    {
        return $query->where('quadrant', $quadrant);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopePermanent($query)
    {
        return $query->where('is_permanent', true);
    }

    public function scopeByFdiCode($query, string $fdiCode)
    {
        return $query->where('fdi_code', $fdiCode);
    }

    public function scopeByPosition($query, int $position)
    {
        return $query->where('position_in_quadrant', $position);
    }

    // Accessors
    public function getQuadrantNumberAttribute(): int
    {
        return match($this->quadrant) {
            'upper_right' => 1,
            'upper_left' => 2,
            'lower_left' => 3,
            'lower_right' => 4,
            default => 0
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->quadrant} {$this->type} {$this->position_in_quadrant}";
    }

    // Static helper methods
    public static function getByFdiCode(string $fdiCode): ?self
    {
        return static::where('fdi_code', $fdiCode)->first();
    }

    public static function getByQuadrantAndPosition(string $quadrant, int $position): ?self
    {
        return static::where('quadrant', $quadrant)
                    ->where('position_in_quadrant', $position)
                    ->first();
    }
}
