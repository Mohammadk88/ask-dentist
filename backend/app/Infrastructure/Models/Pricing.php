<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Infrastructure Model: Pricing
 *
 * This model represents the persistence layer for service pricing entities
 * following hexagonal architecture principles.
 */
class Pricing extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pricing';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Infrastructure\PricingFactory::new();
    }

    protected $fillable = [
        'clinic_id',
        'service_id',
        'base_price',
        'currency',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'conditions',
        'is_negotiable',
        'tooth_modifier',
        'notes',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'conditions' => 'array',
        'is_negotiable' => 'boolean',
        'tooth_modifier' => 'array',
    ];

    protected $dates = [
        'valid_from',
        'valid_until',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Scopes
    public function scopeValid($query, $date = null)
    {
        $date = $date ?: now();

        return $query->where('valid_from', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('valid_until')
                          ->orWhere('valid_until', '>=', $date);
                    });
    }

    public function scopeByCurrency($query, string $currency)
    {
        return $query->where('currency', $currency);
    }

    public function scopeNegotiable($query)
    {
        return $query->where('is_negotiable', true);
    }

    public function scopeWithDiscount($query)
    {
        return $query->where('discount_percentage', '>', 0);
    }

    // Accessors
    public function getDiscountAmountAttribute(): float
    {
        return $this->base_price * ($this->discount_percentage / 100);
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->base_price - $this->discount_amount;
    }

    public function getIsValidAttribute(): bool
    {
        $now = now();
        return $this->valid_from <= $now &&
               (is_null($this->valid_until) || $this->valid_until >= $now);
    }

    public function getIsExpiredAttribute(): bool
    {
        return !is_null($this->valid_until) && $this->valid_until < now();
    }

    /**
     * Calculate price for specific tooth(s) considering tooth modifiers
     */
    public function calculateToothPrice(array $toothCodes): float
    {
        $basePrice = $this->final_price;
        
        if (!$this->tooth_modifier) {
            return $basePrice;
        }

        $totalPrice = 0;
        
        foreach ($toothCodes as $toothCode) {
            $price = $basePrice;
            
            // Apply tooth-specific modifiers
            if (isset($this->tooth_modifier['teeth'][$toothCode])) {
                $modifier = $this->tooth_modifier['teeth'][$toothCode];
                $price = $this->applyModifier($basePrice, $modifier);
            }
            // Apply quadrant modifiers if no specific tooth modifier
            elseif (isset($this->tooth_modifier['quadrants'])) {
                $quadrant = $this->getToothQuadrant($toothCode);
                if (isset($this->tooth_modifier['quadrants'][$quadrant])) {
                    $modifier = $this->tooth_modifier['quadrants'][$quadrant];
                    $price = $this->applyModifier($basePrice, $modifier);
                }
            }
            
            $totalPrice += $price;
        }
        
        return $totalPrice;
    }

    /**
     * Apply price modifier (percentage or fixed amount)
     */
    private function applyModifier(float $basePrice, array $modifier): float
    {
        if ($modifier['type'] === 'percentage') {
            return $basePrice * (1 + $modifier['value'] / 100);
        } elseif ($modifier['type'] === 'fixed') {
            return $basePrice + $modifier['value'];
        }
        
        return $basePrice;
    }

    /**
     * Get quadrant number for FDI tooth code
     */
    private function getToothQuadrant(string $toothCode): int
    {
        $number = (int) $toothCode;
        
        if ($number >= 11 && $number <= 18) return 1; // Upper right
        if ($number >= 21 && $number <= 28) return 2; // Upper left
        if ($number >= 31 && $number <= 38) return 3; // Lower left
        if ($number >= 41 && $number <= 48) return 4; // Lower right
        
        return 1; // Default
    }

    /**
     * Calculate price for a single tooth considering tooth modifiers
     */
    public function calculateSingleToothPrice(int $toothCode): float
    {
        $basePrice = $this->base_price;
        
        if (!$this->tooth_modifier) {
            return $basePrice;
        }

        $toothType = $this->getToothType($toothCode);
        
        if (isset($this->tooth_modifier[$toothType])) {
            $modifier = $this->tooth_modifier[$toothType];
            return $basePrice * $modifier;
        }
        
        return $basePrice;
    }

    /**
     * Get tooth type for FDI tooth code
     */
    public function getToothType(int $toothCode): string
    {
        $position = $toothCode % 10; // Get the position within quadrant
        
        switch ($position) {
            case 1:
            case 2:
                return 'incisor';
            case 3:
                return 'canine';
            case 4:
            case 5:
                return 'premolar';
            case 6:
            case 7:
            case 8:
                return 'molar';
            default:
                return 'incisor'; // Default fallback
        }
    }
}
