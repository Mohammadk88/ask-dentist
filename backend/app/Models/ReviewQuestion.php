<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ReviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_type',
        'options',
        'weights',
        'is_required',
        'is_active',
        'sort_order',
        'category',
    ];

    protected $casts = [
        'options' => 'array',
        'weights' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
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
    public function getQuestionTypeDisplayAttribute(): string
    {
        return match($this->question_type) {
            'rating' => 'Rating (1-5)',
            'text' => 'Text Response',
            'boolean' => 'Yes/No',
            'multiple_choice' => 'Multiple Choice',
            default => ucfirst($this->question_type),
        };
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'communication' => 'Communication',
            'treatment' => 'Treatment Quality',
            'facility' => 'Facility & Environment',
            'overall' => 'Overall Experience',
            default => ucfirst($this->category ?? 'General'),
        };
    }
}
