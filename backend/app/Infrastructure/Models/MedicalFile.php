<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Medical File Model
 *
 * Manages metadata for uploaded medical files with role-based access control
 */
class MedicalFile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'original_name',
        'filename',
        'file_path',
        'file_size',
        'mime_type',
        'file_hash',
        'uploaded_by',
        'related_to_type',
        'related_to_id',
        'file_category',
        'access_level',
        'virus_scan_status',
        'virus_scan_result',
        'expiry_date',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'metadata' => 'array',
        'expiry_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // File categories
    const CATEGORY_XRAY = 'xray';
    const CATEGORY_PHOTO = 'photo';
    const CATEGORY_DOCUMENT = 'document';
    const CATEGORY_REPORT = 'report';
    const CATEGORY_TREATMENT_PLAN = 'treatment_plan';
    const CATEGORY_PRESCRIPTION = 'prescription';

    // Access levels
    const ACCESS_PRIVATE = 'private';
    const ACCESS_CLINIC = 'clinic';
    const ACCESS_DOCTOR = 'doctor';
    const ACCESS_PATIENT = 'patient';

    // Virus scan statuses
    const SCAN_PENDING = 'pending';
    const SCAN_SCANNING = 'scanning';
    const SCAN_CLEAN = 'clean';
    const SCAN_INFECTED = 'infected';
    const SCAN_FAILED = 'failed';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Infrastructure\MedicalFileFactory::new();
    }

    /**
     * Get the user who uploaded the file
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the related model (polymorphic relationship)
     */
    public function relatedTo()
    {
        return $this->morphTo('related_to');
    }

    /**
     * Scope: Files accessible by a specific user
     */
    public function scopeAccessibleBy($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            // User can access their own files
            $q->where('uploaded_by', $user->id);

            // Admin can access all files
            if ($user->hasRole('Admin')) {
                return;
            }

            // Clinic manager can access clinic files
            if ($user->hasRole('ClinicManager') && $user->clinic_id) {
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('access_level', self::ACCESS_CLINIC)
                         ->whereHas('uploader', function ($uploaderQ) use ($user) {
                             $uploaderQ->where('clinic_id', $user->clinic_id);
                         });
                });
            }

            // Doctor can access clinic and patient files
            if ($user->hasRole('Doctor') && $user->clinic_id) {
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->whereIn('access_level', [self::ACCESS_CLINIC, self::ACCESS_DOCTOR])
                         ->whereHas('uploader', function ($uploaderQ) use ($user) {
                             $uploaderQ->where('clinic_id', $user->clinic_id);
                         });
                });
            }

            // Patient can access their own files and shared files
            if ($user->hasRole('Patient')) {
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('access_level', self::ACCESS_PATIENT)
                         ->where(function ($relationQ) use ($user) {
                             $relationQ->where('related_to_type', 'App\Infrastructure\Models\Patient')
                                      ->where('related_to_id', $user->patient?->id);
                         });
                });
            }
        });
    }

    /**
     * Scope: Files by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('file_category', $category);
    }

    /**
     * Scope: Files with clean virus scan
     */
    public function scopeClean($query)
    {
        return $query->where('virus_scan_status', self::SCAN_CLEAN);
    }

    /**
     * Scope: Non-expired files
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>', Carbon::now());
        });
    }

    /**
     * Get the full file path
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/medical/' . $this->file_path);
    }

    /**
     * Get human readable file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file exists on disk
     */
    public function exists(): bool
    {
        return file_exists($this->full_path);
    }

    /**
     * Check if user can access this file
     */
    public function canAccess(User $user): bool
    {
        // Admin can access all files
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Owner can always access
        if ($this->uploaded_by === $user->id) {
            return true;
        }

        // Clinic manager can access clinic files
        if ($user->hasRole('ClinicManager') &&
            $user->clinic_id &&
            $this->access_level === self::ACCESS_CLINIC &&
            $this->uploader->clinic_id === $user->clinic_id) {
            return true;
        }

        // Doctor can access clinic and doctor level files
        if ($user->hasRole('Doctor') &&
            $user->clinic_id &&
            in_array($this->access_level, [self::ACCESS_CLINIC, self::ACCESS_DOCTOR]) &&
            $this->uploader->clinic_id === $user->clinic_id) {
            return true;
        }

        // Patient can access their own files
        if ($user->hasRole('Patient') &&
            $this->access_level === self::ACCESS_PATIENT &&
            $this->related_to_type === 'App\Infrastructure\Models\Patient' &&
            $this->related_to_id === $user->patient?->id) {
            return true;
        }

        return false;
    }

    /**
     * Mark file as virus scanned
     */
    public function markAsScanned(string $result = self::SCAN_CLEAN): void
    {
        $this->update([
            'virus_scan_status' => $result,
            'virus_scan_result' => $result === self::SCAN_CLEAN ? null : 'Virus detected',
        ]);
    }

    /**
     * Generate a secure filename
     */
    public static function generateSecureFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $timestamp = Carbon::now()->format('Y/m/d');
        $randomString = bin2hex(random_bytes(16));

        return "{$timestamp}/{$randomString}.{$extension}";
    }

    /**
     * Get allowed file categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_XRAY => 'X-Ray',
            self::CATEGORY_PHOTO => 'Photo',
            self::CATEGORY_DOCUMENT => 'Document',
            self::CATEGORY_REPORT => 'Report',
            self::CATEGORY_TREATMENT_PLAN => 'Treatment Plan',
            self::CATEGORY_PRESCRIPTION => 'Prescription',
        ];
    }

    /**
     * Get allowed access levels
     */
    public static function getAccessLevels(): array
    {
        return [
            self::ACCESS_PRIVATE => 'Private',
            self::ACCESS_CLINIC => 'Clinic',
            self::ACCESS_DOCTOR => 'Doctor',
            self::ACCESS_PATIENT => 'Patient',
        ];
    }
}
