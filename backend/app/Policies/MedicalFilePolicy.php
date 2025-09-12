<?php

namespace App\Policies;

use App\Infrastructure\Models\MedicalFile;
use App\Infrastructure\Models\User;

/**
 * Medical File Policy
 *
 * Role-based authorization for medical file access:
 * - Doctors can only access their clinic patients and plans
 * - Patients can only view their own requests/plans
 * - Admin has full access
 * - Clinic manager scoped to clinic
 */
class MedicalFilePolicy
{
    /**
     * Determine whether the user can view any medical files.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all files
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Other users can view files they have access to (filtered by scope)
        return $user->hasRole(['Doctor', 'ClinicManager', 'Patient']);
    }

    /**
     * Determine whether the user can view the medical file.
     */
    public function view(User $user, MedicalFile $medicalFile): bool
    {
        return $medicalFile->canAccess($user);
    }

    /**
     * Determine whether the user can create medical files.
     */
    public function create(User $user): bool
    {
        // Admin can always create
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Doctor, clinic manager, and patient can upload files
        return $user->hasRole(['Doctor', 'ClinicManager', 'Patient']);
    }

    /**
     * Determine whether the user can upload files of a specific category.
     */
    public function uploadCategory(User $user, string $category): bool
    {
        // Admin can upload anything
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Category-specific permissions
        $categoryPermissions = [
            MedicalFile::CATEGORY_XRAY => ['Doctor', 'ClinicManager'],
            MedicalFile::CATEGORY_PHOTO => ['Doctor', 'ClinicManager', 'Patient'],
            MedicalFile::CATEGORY_DOCUMENT => ['Doctor', 'ClinicManager', 'Patient'],
            MedicalFile::CATEGORY_REPORT => ['Doctor', 'ClinicManager'],
            MedicalFile::CATEGORY_TREATMENT_PLAN => ['Doctor', 'ClinicManager'],
            MedicalFile::CATEGORY_PRESCRIPTION => ['Doctor', 'ClinicManager'],
        ];

        $allowedRoles = $categoryPermissions[$category] ?? [];

        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can set a specific access level.
     */
    public function setAccessLevel(User $user, string $accessLevel): bool
    {
        return match ($accessLevel) {
            MedicalFile::ACCESS_PRIVATE => true, // Anyone can set private
            MedicalFile::ACCESS_PATIENT => $user->hasRole(['Doctor', 'ClinicManager', 'Admin']),
            MedicalFile::ACCESS_DOCTOR => $user->hasRole(['Doctor', 'ClinicManager', 'Admin']),
            MedicalFile::ACCESS_CLINIC => $user->hasRole(['ClinicManager', 'Admin']),
            default => false,
        };
    }

    /**
     * Determine whether the user can update the medical file.
     */
    public function update(User $user, MedicalFile $medicalFile): bool
    {
        // Admin can update any file
        if ($user->hasRole('Admin')) {
            return true;
        }

        // File owner can update their own files (limited fields)
        if ($medicalFile->uploaded_by === $user->id) {
            return true;
        }

        // Clinic manager can update files within their clinic
        if ($user->hasRole('ClinicManager') && 
            $user->clinic_id && 
            $medicalFile->uploader->clinic_id === $user->clinic_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the medical file.
     */
    public function delete(User $user, MedicalFile $medicalFile): bool
    {
        // Admin can delete any file
        if ($user->hasRole('Admin')) {
            return true;
        }

        // File owner can delete their own files
        if ($medicalFile->uploaded_by === $user->id) {
            return true;
        }

        // Clinic manager can delete files within their clinic
        if ($user->hasRole('ClinicManager') && 
            $user->clinic_id && 
            $medicalFile->uploader->clinic_id === $user->clinic_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can download the medical file.
     */
    public function download(User $user, MedicalFile $medicalFile): bool
    {
        // Same as view permission
        return $this->view($user, $medicalFile);
    }

    /**
     * Determine whether the user can generate signed URLs for the file.
     */
    public function generateSignedUrl(User $user, MedicalFile $medicalFile): bool
    {
        // Same as view permission
        return $this->view($user, $medicalFile);
    }

    /**
     * Determine whether the user can access files related to a specific patient.
     */
    public function accessPatientFiles(User $user, User $patient): bool
    {
        // Admin can access any patient files
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Patient can access their own files
        if ($user->id === $patient->id) {
            return true;
        }

        // Doctor can access files of patients in their clinic
        if ($user->hasRole('Doctor') && 
            $user->clinic_id && 
            $patient->clinic_id === $user->clinic_id) {
            return true;
        }

        // Clinic manager can access files of patients in their clinic
        if ($user->hasRole('ClinicManager') && 
            $user->clinic_id && 
            $patient->clinic_id === $user->clinic_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view virus scan results.
     */
    public function viewVirusScanResults(User $user, MedicalFile $medicalFile): bool
    {
        // Only admin and clinic managers can view virus scan results
        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return true;
        }

        // File owner can view scan results of their own files
        if ($medicalFile->uploaded_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can quarantine files.
     */
    public function quarantine(User $user, MedicalFile $medicalFile): bool
    {
        // Only admin and clinic managers can quarantine files
        return $user->hasRole(['Admin', 'ClinicManager']);
    }

    /**
     * Determine whether the user can restore quarantined files.
     */
    public function restore(User $user, MedicalFile $medicalFile): bool
    {
        // Only admin can restore quarantined files
        return $user->hasRole('Admin');
    }
}