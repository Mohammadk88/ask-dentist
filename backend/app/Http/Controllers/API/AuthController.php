<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;

/**
 * @group Authentication
 *
 * Authentication endpoints for user registration, login, and token management
 */
class AuthController extends Controller
{
    /**
     * Register a new patient
     *
     * Create a new patient account with basic profile information and medical consent
     *
     * @bodyParam name string required The patient's full name. Example: John Doe
     * @bodyParam email string required The patient's email address. Example: john@example.com
     * @bodyParam password string required Password (min 8 characters). Example: SecurePass123
     * @bodyParam password_confirmation string required Password confirmation. Example: SecurePass123
     * @bodyParam phone string required Phone number. Example: +1234567890
     * @bodyParam date_of_birth date required Date of birth (YYYY-MM-DD). Example: 1990-05-15
     * @bodyParam gender string required Gender. Example: male
     * @bodyParam emergency_contact_name string required Emergency contact name. Example: Jane Doe
     * @bodyParam emergency_contact_phone string required Emergency contact phone. Example: +1987654321
     * @bodyParam consent_treatment boolean required Treatment consent. Example: true
     * @bodyParam consent_data_sharing boolean required Data sharing consent. Example: true
     *
     * @response 201 {
     *   "message": "Patient registered successfully",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "user_type": "patient"
     *   },
     *   "token": {
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *     "token_type": "Bearer",
     *     "expires_in": 86400
     *   }
     * }
     *
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function registerPatient(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'consent_treatment' => 'required|boolean',
            'consent_data_sharing' => 'required|boolean',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'dental_history' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'user_type' => 'patient',
                'email_verified_at' => now(), // Auto-verify for demo
            ]);

            // Assign patient role
            $user->assignRole('Patient');

            // Create patient profile
            $patient = Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'medical_history' => $request->medical_history,
                'allergies' => $request->allergies,
                'current_medications' => $request->current_medications,
                'dental_history' => $request->dental_history,
                'insurance_provider' => $request->insurance_provider,
                'insurance_number' => $request->insurance_number,
                'consent_treatment' => $request->consent_treatment,
                'consent_data_sharing' => $request->consent_data_sharing,
            ]);

            // Generate access token
            $token = $user->createToken('Patient Access Token', ['read-profile', 'write-profile', 'read-consultations', 'write-consultations']);

            // Log registration activity
            activity('authentication')
                ->causedBy($user)
                ->withProperties([
                    'registration_type' => 'patient',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Patient registered successfully');

            DB::commit();

            return response()->json([
                'message' => 'Patient registered successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'phone' => $user->phone,
                    'patient_id' => $patient->id,
                ],
                'token' => [
                    'access_token' => $token->accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('passport.expiration_times.access_token', 1440) * 60,
                    'scopes' => $token->token->scopes,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Registration failed',
                'error' => 'An error occurred during registration. Please try again.'
            ], 500);
        }
    }

    /**
     * Register a new doctor
     *
     * Create a new doctor account with professional credentials and specialization
     *
     * @bodyParam name string required The doctor's full name. Example: Dr. Jane Smith
     * @bodyParam email string required The doctor's email address. Example: jane@clinic.com
     * @bodyParam password string required Password (min 8 characters). Example: SecurePass123
     * @bodyParam password_confirmation string required Password confirmation. Example: SecurePass123
     * @bodyParam phone string required Phone number. Example: +1234567890
     * @bodyParam specialization_id integer required Specialization ID. Example: 1
     * @bodyParam license_number string required Medical license number. Example: MD123456
     * @bodyParam years_of_experience integer required Years of experience. Example: 10
     * @bodyParam education string required Education background. Example: Harvard Medical School
     * @bodyParam certifications string optional Professional certifications. Example: Board Certified
     * @bodyParam bio string optional Professional biography. Example: Experienced dentist specializing in...
     * @bodyParam consultation_fee decimal optional Consultation fee. Example: 150.00
     *
     * @response 201 {
     *   "message": "Doctor registered successfully. Account pending verification.",
     *   "user": {
     *     "id": 2,
     *     "name": "Dr. Jane Smith",
     *     "email": "jane@clinic.com",
     *     "user_type": "doctor"
     *   },
     *   "token": {
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *     "token_type": "Bearer",
     *     "expires_in": 86400
     *   }
     * }
     */
    public function registerDoctor(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|string|max:100|unique:doctors',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'education' => 'required|string',
            'certifications' => 'nullable|string',
            'bio' => 'nullable|string|max:1000',
            'consultation_fee' => 'nullable|numeric|min:0|max:9999.99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'user_type' => 'doctor',
                'email_verified_at' => now(), // Auto-verify for demo
            ]);

            // Assign doctor role
            $user->assignRole('Doctor');

            // Create doctor profile
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $request->specialization_id,
                'license_number' => $request->license_number,
                'years_of_experience' => $request->years_of_experience,
                'education' => $request->education,
                'certifications' => $request->certifications,
                'bio' => $request->bio,
                'consultation_fee' => $request->consultation_fee ?? 0,
                'is_verified' => false, // Requires admin verification
                'is_available' => true,
            ]);

            // Generate access token with doctor scopes
            $token = $user->createToken('Doctor Access Token', [
                'read-profile', 'write-profile', 'read-consultations',
                'write-consultations', 'read-medical-records', 'write-medical-records'
            ]);

            // Log registration activity
            activity('authentication')
                ->causedBy($user)
                ->withProperties([
                    'registration_type' => 'doctor',
                    'specialization_id' => $request->specialization_id,
                    'license_number' => $request->license_number,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Doctor registered - pending verification');

            DB::commit();

            return response()->json([
                'message' => 'Doctor registered successfully. Account pending verification.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'phone' => $user->phone,
                    'doctor_id' => $doctor->id,
                    'is_verified' => $doctor->is_verified,
                ],
                'token' => [
                    'access_token' => $token->accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('passport.expiration_times.access_token', 1440) * 60,
                    'scopes' => $token->token->scopes,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Registration failed',
                'error' => 'An error occurred during registration. Please try again.'
            ], 500);
        }
    }

    /**
     * User login
     *
     * Authenticate user and return access token
     *
     * @bodyParam email string required User email address. Example: john@example.com
     * @bodyParam password string required User password. Example: SecurePass123
     * @bodyParam remember_me boolean optional Remember user session. Example: true
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "user_type": "patient"
     *   },
     *   "token": {
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *     "token_type": "Bearer",
     *     "expires_in": 86400
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Invalid credentials"
     * }
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Log failed login attempt
            activity('authentication')
                ->withProperties([
                    'email' => $request->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ])
                ->log('Failed login attempt');

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Determine scopes based on user type and role
        $scopes = $this->getUserScopes($user);

        // Create token with appropriate expiration
        $tokenName = ucfirst($user->user_type) . ' Access Token';
        $expiresIn = $request->remember_me ?
            config('passport.expiration_times.personal_access_token', 525600) :
            config('passport.expiration_times.access_token', 1440);

        $token = $user->createToken($tokenName, $scopes);

        // Update last login timestamp
        // TODO: Add last_login_at column to users table migration
        // $user->update(['last_login_at' => now()]);

        // Log successful login
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'login_type' => $user->user_type,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'remember_me' => $request->remember_me ?? false,
                'scopes' => $scopes,
                'token_id' => $token->token->id,
            ])
            ->log('User login successful');

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'phone' => $user->phone,
                'roles' => $user->roles->pluck('name'),
                'profile_complete' => $this->isProfileComplete($user),
                'verification_status' => [
                    'email_verified' => $user->hasVerifiedEmail(),
                    'phone_verified' => !is_null($user->phone_verified_at),
                ],
            ],
            'token' => [
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_in' => $expiresIn * 60,
                'scopes' => $token->token->scopes ?? [],
                'refresh_token' => 'not_required',
            ]
        ]);
    }

    /**
     * User logout
     *
     * Revoke current access token and optionally all user tokens
     *
     * @authenticated
     *
     * @bodyParam revoke_all boolean optional Revoke all user tokens. Example: true
     *
     * @response 200 {
     *   "message": "Logout successful",
     *   "tokens_revoked": 1
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentToken = $user->token();
        $tokensRevoked = 0;

        if ($request->input('revoke_all', false)) {
            // Revoke all user tokens
            $tokensRevoked = $user->tokens()->count();
            $user->tokens()->delete();
        } else {
            // Revoke only current token
            $currentToken->revoke();
            $tokensRevoked = 1;
        }

        // Log logout activity
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'revoke_all' => $request->input('revoke_all', false),
                'tokens_revoked' => $tokensRevoked,
                'token_id' => $currentToken->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('User logout');

        return response()->json([
            'message' => 'Logout successful',
            'tokens_revoked' => $tokensRevoked
        ]);
    }

    /**
     * Get current user profile
     *
     * Retrieve authenticated user's profile information with role-scoped data
     *
     * @authenticated
     *
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "user_type": "patient",
     *     "verification_status": {
     *       "email_verified": true,
     *       "phone_verified": false
     *     },
     *     "profile": {
     *       "date_of_birth": "1990-05-15",
     *       "gender": "male"
     *     },
     *     "permissions": ["view-own-records", "create-consultation"],
     *     "roles": ["Patient"],
     *     "profile_completion": {
     *       "percentage": 85,
     *       "missing_fields": ["emergency_contact"]
     *     }
     *   }
     * }
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        // Base user data
        $profileData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'is_active' => $user->is_active,
            'verification_status' => [
                'email_verified' => $user->hasVerifiedEmail(),
                'phone_verified' => !is_null($user->phone_verified_at),
                'email_verified_at' => $user->email_verified_at,
                'phone_verified_at' => $user->phone_verified_at,
            ],
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            // 'last_login_at' => $user->last_login_at, // TODO: Add column to users table
        ];

        // Add role-specific profile data
        $profileData = $this->addRoleSpecificData($user, $profileData);

        // Add profile completion information
        $profileData['profile_completion'] = $this->getProfileCompletion($user);

        // Add available actions based on permissions
        $profileData['available_actions'] = $this->getAvailableActions($user);

        return response()->json([
            'user' => $profileData
        ]);
    }

    /**
     * Refresh access token
     *
     * Generate a new access token using refresh token
     *
     * @authenticated
     *
     * @response 200 {
     *   "token": {
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *     "token_type": "Bearer",
     *     "expires_in": 86400,
     *     "scopes": ["read-profile", "write-profile"]
     *   },
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe"
     *   }
     * }
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentToken = $user->token();

        // Check if current token can be refreshed
        if (!$currentToken) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        // Store token info before revocation
        $tokenScopes = $currentToken->scopes ?? [];
        $tokenName = $currentToken->name ?? ucfirst($user->user_type) . ' Access Token';

        // Revoke current token
        $currentToken->revoke();

        // Create new token with same or updated scopes
        $scopes = $this->getUserScopes($user);
        $token = $user->createToken($tokenName, $scopes);

        // Log token refresh
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'old_token_id' => $currentToken->id,
                'new_token_id' => $token->token->id,
                'old_scopes' => $tokenScopes,
                'new_scopes' => $scopes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Token refreshed');

        return response()->json([
            'token' => [
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_in' => config('passport.expiration_times.access_token', 1440) * 60,
                'scopes' => $token->token->scopes,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'roles' => $user->roles->pluck('name'),
            ]
        ]);
    }

    /**
     * Get user-specific OAuth scopes
     */
    private function getUserScopes(User $user): array
    {
        $baseScopes = ['read-profile', 'write-profile'];

        if ($user->hasRole('Patient')) {
            return array_merge($baseScopes, [
                'read-consultations', 'write-consultations',
                'read-messages', 'write-messages'
            ]);
        }

        if ($user->hasRole('Doctor')) {
            return array_merge($baseScopes, [
                'read-consultations', 'write-consultations',
                'read-messages', 'write-messages',
                'read-medical-records', 'write-medical-records'
            ]);
        }

        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            return array_merge($baseScopes, ['admin-access']);
        }

        return $baseScopes;
    }

    /**
     * Add role-specific data to user profile
     */
    private function addRoleSpecificData(User $user, array $profileData): array
    {
        if ($user->hasRole('Patient')) {
            $profileData['patient_profile'] = $this->getPatientProfileData($user);
        }

        if ($user->hasRole('Doctor')) {
            $profileData['doctor_profile'] = $this->getDoctorProfileData($user);
        }

        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            $profileData['admin_data'] = $this->getAdminData($user);
        }

        return $profileData;
    }

    /**
     * Get patient-specific profile data
     */
    private function getPatientProfileData(User $user): array
    {
        if (!$user->profilePatient) {
            return ['profile_exists' => false];
        }

        $patient = $user->profilePatient;

        return [
            'profile_exists' => true,
            'id' => $patient->id,
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
            'emergency_contact_name' => $patient->emergency_contact_name,
            'emergency_contact_phone' => $patient->emergency_contact_phone,
            'insurance_provider' => $patient->insurance_provider,
            'insurance_number' => $patient->insurance_number,
            'consents' => [
                'treatment' => $patient->consent_treatment,
                'data_sharing' => $patient->consent_data_sharing,
            ],
            'has_given_consent' => $patient->hasGivenConsent(),
            // Don't expose sensitive medical data unless specifically requested
            'has_medical_history' => !empty($patient->medical_history),
            'has_allergies' => !empty($patient->allergies),
            'has_current_medications' => !empty($patient->current_medications),
            'has_dental_history' => !empty($patient->dental_history),
        ];
    }

    /**
     * Get doctor-specific profile data
     */
    private function getDoctorProfileData(User $user): array
    {
        if (!$user->profileDoctor) {
            return ['profile_exists' => false];
        }

        $doctor = $user->profileDoctor;

        return [
            'profile_exists' => true,
            'id' => $doctor->id,
            'specialization' => $doctor->specialization ? [
                'id' => $doctor->specialization->id,
                'name' => $doctor->specialization->name,
            ] : null,
            'clinic' => $doctor->clinic ? [
                'id' => $doctor->clinic->id,
                'name' => $doctor->clinic->name,
                'location' => $doctor->clinic->location,
            ] : null,
            'license_number' => $doctor->license_number,
            'years_of_experience' => $doctor->years_of_experience,
            'education' => $doctor->education,
            'bio' => $doctor->bio,
            'consultation_fee' => $doctor->consultation_fee,
            'is_verified' => $doctor->is_verified,
            'is_available' => $doctor->is_available,
            'rating' => $doctor->rating,
            'total_consultations' => $doctor->total_consultations,
        ];
    }

    /**
     * Get admin-specific data
     */
    private function getAdminData(User $user): array
    {
        $data = [
            'can_manage_users' => $user->can('manage-users'),
            'can_manage_doctors' => $user->can('manage-doctors'),
            'can_manage_patients' => $user->can('manage-patients'),
            'has_admin_access' => $user->can('admin-access'),
        ];

        if ($user->hasRole('ClinicManager') && $user->profileDoctor && $user->profileDoctor->clinic) {
            $data['managed_clinic'] = [
                'id' => $user->profileDoctor->clinic->id,
                'name' => $user->profileDoctor->clinic->name,
                'location' => $user->profileDoctor->clinic->location,
            ];
        }

        return $data;
    }

    /**
     * Calculate profile completion percentage and missing fields
     */
    private function getProfileCompletion(User $user): array
    {
        $totalFields = 0;
        $completedFields = 0;
        $missingFields = [];

        // Base user fields
        $userFields = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ];

        foreach ($userFields as $field => $value) {
            $totalFields++;
            if (!empty($value)) {
                $completedFields++;
            } else {
                $missingFields[] = $field;
            }
        }

        // Email verification
        $totalFields++;
        if ($user->hasVerifiedEmail()) {
            $completedFields++;
        } else {
            $missingFields[] = 'email_verification';
        }

        // Role-specific fields
        if ($user->hasRole('Patient')) {
            $completion = $this->getPatientProfileCompletion($user);
            $totalFields += $completion['total'];
            $completedFields += $completion['completed'];
            $missingFields = array_merge($missingFields, $completion['missing']);
        }

        if ($user->hasRole('Doctor')) {
            $completion = $this->getDoctorProfileCompletion($user);
            $totalFields += $completion['total'];
            $completedFields += $completion['completed'];
            $missingFields = array_merge($missingFields, $completion['missing']);
        }

        $percentage = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 100;

        return [
            'percentage' => $percentage,
            'completed_fields' => $completedFields,
            'total_fields' => $totalFields,
            'missing_fields' => $missingFields,
            'is_complete' => $percentage === 100,
        ];
    }

    /**
     * Get patient profile completion details
     */
    private function getPatientProfileCompletion(User $user): array
    {
        $totalFields = 0;
        $completedFields = 0;
        $missingFields = [];

        if (!$user->profilePatient) {
            return [
                'total' => 6,
                'completed' => 0,
                'missing' => ['patient_profile', 'date_of_birth', 'gender', 'emergency_contact', 'consents', 'phone_verification']
            ];
        }

        $patient = $user->profilePatient;
        $fields = [
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
            'emergency_contact_name' => $patient->emergency_contact_name,
            'emergency_contact_phone' => $patient->emergency_contact_phone,
        ];

        foreach ($fields as $field => $value) {
            $totalFields++;
            if (!empty($value)) {
                $completedFields++;
            } else {
                $missingFields[] = $field;
            }
        }

        // Consent fields
        $totalFields++;
        if ($patient->hasGivenConsent()) {
            $completedFields++;
        } else {
            $missingFields[] = 'treatment_consent';
        }

        // Phone verification
        $totalFields++;
        if ($user->hasVerifiedPhone()) {
            $completedFields++;
        } else {
            $missingFields[] = 'phone_verification';
        }

        return [
            'total' => $totalFields,
            'completed' => $completedFields,
            'missing' => $missingFields,
        ];
    }

    /**
     * Get doctor profile completion details
     */
    private function getDoctorProfileCompletion(User $user): array
    {
        $totalFields = 0;
        $completedFields = 0;
        $missingFields = [];

        if (!$user->profileDoctor) {
            return [
                'total' => 7,
                'completed' => 0,
                'missing' => ['doctor_profile', 'specialization', 'license_number', 'education', 'experience', 'verification', 'clinic']
            ];
        }

        $doctor = $user->profileDoctor;
        $fields = [
            'specialization_id' => $doctor->specialization_id,
            'license_number' => $doctor->license_number,
            'education' => $doctor->education,
            'years_of_experience' => $doctor->years_of_experience,
            'bio' => $doctor->bio,
        ];

        foreach ($fields as $field => $value) {
            $totalFields++;
            if (!empty($value)) {
                $completedFields++;
            } else {
                $missingFields[] = str_replace('_id', '', $field);
            }
        }

        // Verification status
        $totalFields++;
        if ($doctor->is_verified) {
            $completedFields++;
        } else {
            $missingFields[] = 'doctor_verification';
        }

        // Clinic assignment
        $totalFields++;
        if ($doctor->clinic_id) {
            $completedFields++;
        } else {
            $missingFields[] = 'clinic_assignment';
        }

        return [
            'total' => $totalFields,
            'completed' => $completedFields,
            'missing' => $missingFields,
        ];
    }

    /**
     * Get available actions for the user based on their role and permissions
     */
    private function getAvailableActions(User $user): array
    {
        $actions = [];

        // Common actions
        $actions['profile'] = [
            'update_profile' => true,
            'change_password' => true,
            'upload_avatar' => true,
        ];

        if (!$user->hasVerifiedEmail()) {
            $actions['verification'][] = 'verify_email';
        }

        if (!$user->hasVerifiedPhone()) {
            $actions['verification'][] = 'verify_phone';
        }

        // Role-specific actions
        if ($user->hasRole('Patient')) {
            $actions['patient'] = [
                'create_treatment_request' => $user->can('create-consultation'),
                'view_treatment_history' => true,
                'update_medical_history' => true,
                'manage_consents' => true,
            ];
        }

        if ($user->hasRole('Doctor')) {
            $actions['doctor'] = [
                'view_assigned_requests' => $user->can('doctor-access'),
                'create_treatment_plans' => $user->can('create-consultation-response'),
                'update_availability' => true,
                'view_patients' => $user->can('manage-patients'),
            ];
        }

        if ($user->hasRole(['Admin', 'ClinicManager'])) {
            $actions['admin'] = [
                'manage_users' => $user->can('manage-users'),
                'manage_doctors' => $user->can('manage-doctors'),
                'manage_patients' => $user->can('manage-patients'),
                'view_reports' => $user->can('admin-access'),
            ];
        }

        return $actions;
    }

    /**
     * Check if user profile is complete
     */
    private function isProfileComplete(User $user): bool
    {
        $completion = $this->getProfileCompletion($user);
        return $completion['is_complete'];
    }

    /**
     * Get current token information
     *
     * Returns detailed information about the current access token
     *
     * @authenticated
     *
     * @response 200 {
     *   "token": {
     *     "id": "token-id",
     *     "name": "Patient Access Token",
     *     "scopes": ["read-profile", "write-profile"],
     *     "created_at": "2024-01-15T10:30:00Z",
     *     "expires_at": "2024-01-16T10:30:00Z",
     *     "can_refresh": true,
     *     "is_revoked": false
     *   },
     *   "session": {
     *     "ip_address": "192.168.1.1",
     *     "user_agent": "Mozilla/5.0...",
     *     "last_activity": "2024-01-15T14:30:00Z"
     *   }
     * }
     */
    public function tokenInfo(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $user->token();

        if (!$token) {
            return response()->json([
                'message' => 'No active token found'
            ], 401);
        }

        // Calculate time until expiration
        $expiresAt = $token->expires_at;
        $timeToExpiry = $expiresAt ? $expiresAt->diffInSeconds(now()) : null;

        return response()->json([
            'token' => [
                'id' => $token->id,
                'name' => $token->name,
                'scopes' => $token->scopes ?? [],
                'created_at' => $token->created_at,
                'expires_at' => $token->expires_at,
                'time_to_expiry_seconds' => $timeToExpiry > 0 ? $timeToExpiry : 0,
                'can_refresh' => !$token->revoked && $timeToExpiry > 0,
                'is_revoked' => $token->revoked,
            ],
            'session' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity' => now(),
            ]
        ]);
    }

    /**
     * List all active tokens for the user
     *
     * Returns all active access tokens for the current user
     *
     * @authenticated
     *
     * @response 200 {
     *   "tokens": [
     *     {
     *       "id": "token-id-1",
     *       "name": "Patient Access Token",
     *       "scopes": ["read-profile"],
     *       "created_at": "2024-01-15T10:30:00Z",
     *       "last_used_at": "2024-01-15T14:30:00Z",
     *       "is_current": true
     *     }
     *   ],
     *   "total_tokens": 1
     * }
     */
    public function tokens(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->token()->id ?? null;

        $tokens = $user->tokens()
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($token) use ($currentTokenId) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'scopes' => $token->scopes ?? [],
                    'created_at' => $token->created_at,
                    'last_used_at' => $token->updated_at,
                    'expires_at' => $token->expires_at,
                    'is_current' => $token->id === $currentTokenId,
                ];
            });

        return response()->json([
            'tokens' => $tokens,
            'total_tokens' => $tokens->count()
        ]);
    }

    /**
     * Revoke a specific token
     *
     * Revoke a specific access token by ID
     *
     * @authenticated
     *
     * @bodyParam token_id string required The ID of the token to revoke
     *
     * @response 200 {
     *   "message": "Token revoked successfully"
     * }
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $request->validate([
            'token_id' => 'required|string'
        ]);

        $user = $request->user();
        $tokenId = $request->input('token_id');

        // Find the token
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return response()->json([
                'message' => 'Token not found'
            ], 404);
        }

        if ($token->revoked) {
            return response()->json([
                'message' => 'Token already revoked'
            ], 400);
        }

        // Revoke the token
        $token->revoke();

        // Log token revocation
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'revoked_token_id' => $tokenId,
                'token_name' => $token->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Specific token revoked');

        return response()->json([
            'message' => 'Token revoked successfully'
        ]);
    }
}
