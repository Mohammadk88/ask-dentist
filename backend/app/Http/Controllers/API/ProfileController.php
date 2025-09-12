<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * @group Profile Management
 *
 * User profile management endpoints for patients and doctors
 */
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Update user profile
     *
     * Update basic user information
     *
     * @authenticated
     *
     * @bodyParam name string optional Full name. Example: John Smith
     * @bodyParam phone string optional Phone number. Example: +1234567890
     * @bodyParam current_password string optional Current password (required if changing password). Example: CurrentPass123
     * @bodyParam password string optional New password. Example: NewPass123
     * @bodyParam password_confirmation string optional New password confirmation. Example: NewPass123
     *
     * @response 200 {
     *   "message": "Profile updated successfully",
     *   "user": {
     *     "id": 1,
     *     "name": "John Smith",
     *     "email": "john@example.com",
     *     "phone": "+1234567890"
     *   }
     * }
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ];

        // Add password validation if password change is requested
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }
        }

        // Update user data
        $updateData = $request->only(['name', 'phone']);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Log profile update
        activity('profile')
            ->causedBy($user)
            ->withProperties([
                'updated_fields' => array_keys($updateData),
                'ip_address' => $request->ip(),
            ])
            ->log('User profile updated');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
            ]
        ]);
    }

    /**
     * Update patient profile
     *
     * Update patient-specific information
     *
     * @authenticated
     *
     * @bodyParam date_of_birth date optional Date of birth. Example: 1990-05-15
     * @bodyParam gender string optional Gender. Example: male
     * @bodyParam emergency_contact_name string optional Emergency contact name. Example: Jane Doe
     * @bodyParam emergency_contact_phone string optional Emergency contact phone. Example: +1987654321
     * @bodyParam medical_history string optional Medical history. Example: No significant medical history
     * @bodyParam allergies string optional Known allergies. Example: Penicillin allergy
     * @bodyParam current_medications string optional Current medications. Example: Aspirin 81mg daily
     * @bodyParam dental_history string optional Dental history. Example: Regular cleanings, no major procedures
     * @bodyParam insurance_provider string optional Insurance provider. Example: Blue Cross Blue Shield
     * @bodyParam insurance_number string optional Insurance number. Example: BC123456789
     *
     * @response 200 {
     *   "message": "Patient profile updated successfully",
     *   "patient": {
     *     "id": 1,
     *     "date_of_birth": "1990-05-15",
     *     "gender": "male",
     *     "medical_history": "Updated medical history"
     *   }
     * }
     */
    public function updatePatientProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->user_type !== 'patient') {
            return response()->json([
                'message' => 'Access denied. Patient account required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|string|in:male,female,other',
            'emergency_contact_name' => 'sometimes|string|max:255',
            'emergency_contact_phone' => 'sometimes|string|max:20',
            'medical_history' => 'sometimes|string',
            'allergies' => 'sometimes|string',
            'current_medications' => 'sometimes|string',
            'dental_history' => 'sometimes|string',
            'insurance_provider' => 'sometimes|string|max:255',
            'insurance_number' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'message' => 'Patient profile not found'
            ], 404);
        }

        $updateData = $request->only([
            'date_of_birth', 'gender', 'emergency_contact_name', 'emergency_contact_phone',
            'medical_history', 'allergies', 'current_medications', 'dental_history',
            'insurance_provider', 'insurance_number'
        ]);

        $patient->update($updateData);

        // Log profile update
        activity('medical_records')
            ->causedBy($user)
            ->performedOn($patient)
            ->withProperties([
                'updated_fields' => array_keys($updateData),
                'ip_address' => $request->ip(),
            ])
            ->log('Patient profile updated');

        return response()->json([
            'message' => 'Patient profile updated successfully',
            'patient' => $patient->fresh()
        ]);
    }

    /**
     * Update doctor profile
     *
     * Update doctor-specific information
     *
     * @authenticated
     *
     * @bodyParam specialization_id integer optional Specialization ID. Example: 2
     * @bodyParam years_of_experience integer optional Years of experience. Example: 12
     * @bodyParam education string optional Education background. Example: Johns Hopkins School of Medicine
     * @bodyParam certifications string optional Professional certifications. Example: Board Certified in Orthodontics
     * @bodyParam bio string optional Professional biography. Example: Experienced dentist with focus on pediatric care
     * @bodyParam consultation_fee decimal optional Consultation fee. Example: 175.00
     * @bodyParam is_available boolean optional Availability status. Example: true
     *
     * @response 200 {
     *   "message": "Doctor profile updated successfully",
     *   "doctor": {
     *     "id": 1,
     *     "specialization_id": 2,
     *     "years_of_experience": 12,
     *     "consultation_fee": "175.00",
     *     "is_available": true
     *   }
     * }
     */
    public function updateDoctorProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->user_type !== 'doctor') {
            return response()->json([
                'message' => 'Access denied. Doctor account required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'specialization_id' => 'sometimes|exists:specializations,id',
            'years_of_experience' => 'sometimes|integer|min:0|max:50',
            'education' => 'sometimes|string',
            'certifications' => 'sometimes|string',
            'bio' => 'sometimes|string|max:1000',
            'consultation_fee' => 'sometimes|numeric|min:0|max:9999.99',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $doctor = $user->doctor;
        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor profile not found'
            ], 404);
        }

        $updateData = $request->only([
            'specialization_id', 'years_of_experience', 'education',
            'certifications', 'bio', 'consultation_fee', 'is_available'
        ]);

        $doctor->update($updateData);

        // Log profile update
        activity('profile')
            ->causedBy($user)
            ->performedOn($doctor)
            ->withProperties([
                'updated_fields' => array_keys($updateData),
                'ip_address' => $request->ip(),
            ])
            ->log('Doctor profile updated');

        return response()->json([
            'message' => 'Doctor profile updated successfully',
            'doctor' => $doctor->fresh()->load('specialization')
        ]);
    }

    /**
     * Upload profile picture
     *
     * Upload and update user profile picture
     *
     * @authenticated
     *
     * @bodyParam profile_picture file required Profile picture image (max 2MB, jpg/png). Example: profile.jpg
     *
     * @response 200 {
     *   "message": "Profile picture updated successfully",
     *   "profile_picture_url": "https://example.com/storage/profile-pictures/user-123.jpg"
     * }
     */
    public function uploadProfilePicture(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        try {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');

            // Update user profile
            $user->update(['profile_picture' => $path]);

            // Log file upload
            activity('file_access')
                ->causedBy($user)
                ->withProperties([
                    'action' => 'uploaded',
                    'file_type' => 'profile_picture',
                    'file_path' => $path,
                    'ip_address' => $request->ip(),
                ])
                ->log('Profile picture uploaded');

            return response()->json([
                'message' => 'Profile picture updated successfully',
                'profile_picture_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload profile picture',
                'error' => 'An error occurred during upload. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete profile picture
     *
     * Remove user profile picture
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Profile picture deleted successfully"
     * }
     */
    public function deleteProfilePicture(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->profile_picture) {
            return response()->json([
                'message' => 'No profile picture to delete'
            ], 404);
        }

        try {
            // Delete file from storage
            Storage::disk('public')->delete($user->profile_picture);

            // Update user profile
            $user->update(['profile_picture' => null]);

            // Log file deletion
            activity('file_access')
                ->causedBy($user)
                ->withProperties([
                    'action' => 'deleted',
                    'file_type' => 'profile_picture',
                    'ip_address' => $request->ip(),
                ])
                ->log('Profile picture deleted');

            return response()->json([
                'message' => 'Profile picture deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete profile picture'
            ], 500);
        }
    }

    /**
     * Get available specializations
     *
     * Retrieve list of all active medical specializations
     *
     * @response 200 {
     *   "specializations": [
     *     {
     *       "id": 1,
     *       "name": "General Dentistry",
     *       "description": "General dental care and procedures",
     *       "available_doctors_count": 5
     *     }
     *   ]
     * }
     */
    public function getSpecializations(): JsonResponse
    {
        $specializations = Specialization::active()
            ->withCount(['doctors as available_doctors_count' => function ($query) {
                $query->where('is_verified', true)->where('is_available', true);
            }])
            ->get();

        return response()->json([
            'specializations' => $specializations
        ]);
    }

    /**
     * Update patient consent
     *
     * Update patient treatment and data sharing consent
     *
     * @authenticated
     *
     * @bodyParam consent_treatment boolean required Treatment consent. Example: true
     * @bodyParam consent_data_sharing boolean required Data sharing consent. Example: true
     *
     * @response 200 {
     *   "message": "Consent updated successfully",
     *   "consent": {
     *     "consent_treatment": true,
     *     "consent_data_sharing": true
     *   }
     * }
     */
    public function updateConsent(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->user_type !== 'patient') {
            return response()->json([
                'message' => 'Access denied. Patient account required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'consent_treatment' => 'required|boolean',
            'consent_data_sharing' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'message' => 'Patient profile not found'
            ], 404);
        }

        $patient->update([
            'consent_treatment' => $request->consent_treatment,
            'consent_data_sharing' => $request->consent_data_sharing,
        ]);

        // Log consent update - important for compliance
        activity('medical_records')
            ->causedBy($user)
            ->performedOn($patient)
            ->withProperties([
                'consent_treatment' => $request->consent_treatment,
                'consent_data_sharing' => $request->consent_data_sharing,
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ])
            ->log('Patient consent updated');

        return response()->json([
            'message' => 'Consent updated successfully',
            'consent' => [
                'consent_treatment' => $patient->consent_treatment,
                'consent_data_sharing' => $patient->consent_data_sharing,
            ]
        ]);
    }
}
