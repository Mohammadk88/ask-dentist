<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\UseCases\RegisterPatientUseCase;
use App\Application\UseCases\RegisterDoctorUseCase;
use App\Application\UseCases\LoginUseCase;
use App\Application\DTOs\RegisterPatientDTO;
use App\Application\DTOs\RegisterDoctorDTO;
use App\Application\DTOs\LoginDTO;
use Laravel\Passport\HasApiTokens;

/**
 * @group Authentication (Hexagonal)
 *
 * Clean architecture implementation of authentication endpoints using hexagonal architecture pattern.
 * This controller demonstrates proper separation of concerns by using Domain entities, Application use cases,
 * and Infrastructure repositories instead of directly accessing Eloquent models.
 */
class HexagonalAuthController extends Controller
{
    /**
     * Register a new patient
     *
     * This endpoint demonstrates hexagonal architecture by using:
     * - Application layer (RegisterPatientUseCase)
     * - Domain entities (User, Patient) with value objects (Email, Phone)
     * - Infrastructure layer (EloquentUserRepository, EloquentPatientRepository)
     *
     * @bodyParam first_name string required The patient's first name. Example: John
     * @bodyParam last_name string required The patient's last name. Example: Doe
     * @bodyParam email string required The patient's email address. Example: john.doe@example.com
     * @bodyParam password string required The patient's password (minimum 8 characters). Example: password123
     * @bodyParam phone string required The patient's phone number. Example: +1234567890
     * @bodyParam date_of_birth string required The patient's date of birth (YYYY-MM-DD). Example: 1990-01-15
     * @bodyParam gender string required The patient's gender. Example: male
     * @bodyParam emergency_contact_name string required Emergency contact name. Example: Jane Doe
     * @bodyParam emergency_contact_phone string required Emergency contact phone. Example: +1234567891
     * @bodyParam consent_treatment boolean required Consent for treatment. Example: true
     * @bodyParam consent_data_sharing boolean required Consent for data sharing. Example: true
     * @bodyParam medical_history string optional Medical history details. Example: No known medical conditions
     * @bodyParam allergies string optional Known allergies. Example: Penicillin
     * @bodyParam current_medications string optional Current medications. Example: None
     * @bodyParam dental_history string optional Dental history. Example: Regular cleanings
     * @bodyParam insurance_provider string optional Insurance provider. Example: Blue Cross
     * @bodyParam insurance_number string optional Insurance number. Example: BC123456789
     *
     * @response 201 {
     *   "message": "Patient registered successfully",
     *   "user": {
     *     "id": 1,
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "email": "john.doe@example.com",
     *     "role": "patient"
     *   },
     *   "patient": {
     *     "id": 1,
     *     "user_id": 1,
     *     "date_of_birth": "1990-01-15",
     *     "gender": "male",
     *     "age": 34
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function registerPatient(Request $request, RegisterPatientUseCase $useCase): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
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
            'insurance_number' => 'nullable|string|max:255',
        ]);

        try {
            $dto = new RegisterPatientDTO(
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                password: $request->password,
                phone: $request->phone,
                dateOfBirth: $request->date_of_birth,
                gender: $request->gender,
                emergencyContactName: $request->emergency_contact_name,
                emergencyContactPhone: $request->emergency_contact_phone,
                consentTreatment: $request->consent_treatment,
                consentDataSharing: $request->consent_data_sharing,
                medicalHistory: $request->medical_history,
                allergies: $request->allergies,
                currentMedications: $request->current_medications,
                dentalHistory: $request->dental_history,
                insuranceProvider: $request->insurance_provider,
                insuranceNumber: $request->insurance_number
            );

            $result = $useCase->execute($dto);

            return response()->json([
                'message' => 'Patient registered successfully',
                'user' => [
                    'id' => $result['user']->getId()->getValue(),
                    'first_name' => $result['user']->getFirstName(),
                    'last_name' => $result['user']->getLastName(),
                    'email' => $result['user']->getEmail()->getValue(),
                    'role' => $result['user']->getRole(),
                ],
                'patient' => [
                    'id' => $result['patient']->getId()->getValue(),
                    'user_id' => $result['patient']->getUserId()->getValue(),
                    'date_of_birth' => $result['patient']->getDateOfBirth()->format('Y-m-d'),
                    'gender' => $result['patient']->getGender(),
                    'age' => $result['patient']->getAge(),
                ]
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register a new doctor
     *
     * @bodyParam first_name string required The doctor's first name. Example: Dr. Sarah
     * @bodyParam last_name string required The doctor's last name. Example: Johnson
     * @bodyParam email string required The doctor's email address. Example: dr.sarah@example.com
     * @bodyParam password string required The doctor's password (minimum 8 characters). Example: password123
     * @bodyParam phone string required The doctor's phone number. Example: +1234567890
     * @bodyParam specialization_id integer required The doctor's specialization ID. Example: 1
     * @bodyParam license_number string required The doctor's license number. Example: DL123456
     * @bodyParam years_of_experience string required Years of experience. Example: 10
     * @bodyParam consultation_fee number required Consultation fee. Example: 150.00
     * @bodyParam currency string optional Currency code. Example: USD
     * @bodyParam bio string optional Doctor's bio. Example: Experienced dentist specializing in oral surgery
     * @bodyParam education string optional Education details. Example: DDS from Harvard School of Dental Medicine
     * @bodyParam certifications string optional Certifications. Example: Board certified in oral surgery
     */
    public function registerDoctor(Request $request, RegisterDoctorUseCase $useCase): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'specialization_id' => 'required|integer|exists:specializations,id',
            'license_number' => 'required|string|max:255',
            'years_of_experience' => 'required|string|max:10',
            'consultation_fee' => 'required|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);

        try {
            $dto = new RegisterDoctorDTO(
                firstName: $request->first_name,
                lastName: $request->last_name,
                email: $request->email,
                password: $request->password,
                phone: $request->phone,
                specializationId: $request->specialization_id,
                licenseNumber: $request->license_number,
                yearsOfExperience: $request->years_of_experience,
                consultationFee: $request->consultation_fee,
                currency: $request->currency ?? 'USD',
                bio: $request->bio,
                education: $request->education,
                certifications: $request->certifications
            );

            $result = $useCase->execute($dto);

            return response()->json([
                'message' => 'Doctor registered successfully',
                'user' => [
                    'id' => $result['user']->getId()->getValue(),
                    'first_name' => $result['user']->getFirstName(),
                    'last_name' => $result['user']->getLastName(),
                    'email' => $result['user']->getEmail()->getValue(),
                    'role' => $result['user']->getRole(),
                ],
                'doctor' => [
                    'id' => $result['doctor']->getId()->getValue(),
                    'user_id' => $result['doctor']->getUserId()->getValue(),
                    'specialization_id' => $result['doctor']->getSpecializationId()->getValue(),
                    'license_number' => $result['doctor']->getLicenseNumber(),
                    'consultation_fee' => $result['doctor']->getConsultationFee()->format(),
                    'is_verified' => $result['doctor']->isVerified(),
                ]
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * User login
     *
     * @bodyParam email string required The user's email address. Example: john.doe@example.com
     * @bodyParam password string required The user's password. Example: password123
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "user": {
     *     "id": 1,
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "email": "john.doe@example.com",
     *     "role": "patient"
     *   },
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *   "token_type": "Bearer",
     *   "expires_in": 31536000
     * }
     */
    public function login(Request $request, LoginUseCase $useCase): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $dto = new LoginDTO(
                email: $request->email,
                password: $request->password
            );

            $domainUser = $useCase->execute($dto);

            // Get the Eloquent model for token creation
            $eloquentUser = \App\Models\User::find($domainUser->getId()->getValue());
            if (!$eloquentUser) {
                throw new \DomainException('User not found');
            }

            // Create OAuth2 token using Laravel Passport
            $tokenResult = $eloquentUser->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = now()->addYear();
            $token->save();

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $domainUser->getId()->getValue(),
                    'first_name' => $domainUser->getFirstName(),
                    'last_name' => $domainUser->getLastName(),
                    'email' => $domainUser->getEmail()->getValue(),
                    'role' => $domainUser->getRole(),
                ],
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
            ]);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
