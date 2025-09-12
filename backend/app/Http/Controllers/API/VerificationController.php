<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\PhoneVerificationNotification;

/**
 * @group Email & Phone Verification
 *
 * Email and phone verification endpoints for user account verification
 */
class VerificationController extends Controller
{
    /**
     * Send email verification
     *
     * Send an email verification link to the user's email address
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Verification email sent successfully"
     * }
     *
     * @response 409 {
     *   "message": "Email is already verified"
     * }
     */
    public function sendEmailVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified'
            ], 409);
        }

        // Generate verification token
        $verificationToken = VerificationToken::generateEmailToken($user);

        // Send verification email
        $user->notify(new EmailVerificationNotification($verificationToken->token));

        // Log verification email sent
        activity('verification')
            ->causedBy($user)
            ->withProperties([
                'type' => 'email',
                'contact' => $user->email,
                'ip_address' => $request->ip(),
            ])
            ->log('Email verification sent');

        return response()->json([
            'message' => 'Verification email sent successfully'
        ]);
    }

    /**
     * Verify email
     *
     * Verify the user's email address using the verification token
     *
     * @bodyParam token string required The email verification token. Example: uuid-token-here
     * @bodyParam email string required The user's email address. Example: john@example.com
     *
     * @response 200 {
     *   "message": "Email verified successfully"
     * }
     *
     * @response 400 {
     *   "message": "Invalid or expired verification token"
     * }
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $verificationToken = VerificationToken::findValidToken(
            $request->token,
            'email',
            $request->email
        );

        if (!$verificationToken) {
            return response()->json([
                'message' => 'Invalid or expired verification token'
            ], 400);
        }

        $user = $verificationToken->user;

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        $verificationToken->markAsVerified();

        // Log email verification success
        activity('verification')
            ->causedBy($user)
            ->withProperties([
                'type' => 'email',
                'contact' => $user->email,
                'ip_address' => $request->ip(),
            ])
            ->log('Email verified successfully');

        return response()->json([
            'message' => 'Email verified successfully'
        ]);
    }

    /**
     * Send phone verification
     *
     * Send an SMS verification code to the user's phone number
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Verification code sent to your phone"
     * }
     *
     * @response 409 {
     *   "message": "Phone number is already verified"
     * }
     */
    public function sendPhoneVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedPhone()) {
            return response()->json([
                'message' => 'Phone number is already verified'
            ], 409);
        }

        if (empty($user->phone)) {
            return response()->json([
                'message' => 'Phone number not found in profile'
            ], 400);
        }

        // Generate verification token
        $verificationToken = VerificationToken::generatePhoneToken($user);

        // Send verification SMS (in production, use SMS service like Twilio)
        // For now, we'll just log it
        \Log::info("Phone verification code for {$user->phone}: {$verificationToken->token}");

        // In production, uncomment this:
        // $user->notify(new PhoneVerificationNotification($verificationToken->token));

        // Log verification SMS sent
        activity('verification')
            ->causedBy($user)
            ->withProperties([
                'type' => 'phone',
                'contact' => $user->phone,
                'ip_address' => $request->ip(),
            ])
            ->log('Phone verification code sent');

        return response()->json([
            'message' => 'Verification code sent to your phone',
            'debug' => app()->environment('local') ? ['code' => $verificationToken->token] : null
        ]);
    }

    /**
     * Verify phone
     *
     * Verify the user's phone number using the 6-digit verification code
     *
     * @bodyParam code string required The 6-digit verification code. Example: 123456
     * @bodyParam phone string required The user's phone number. Example: +1234567890
     *
     * @response 200 {
     *   "message": "Phone number verified successfully"
     * }
     *
     * @response 400 {
     *   "message": "Invalid or expired verification code"
     * }
     *
     * @response 429 {
     *   "message": "Too many verification attempts. Please request a new code."
     * }
     */
    public function verifyPhone(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $verificationToken = VerificationToken::findValidToken(
            $request->code,
            'phone',
            $request->phone
        );

        if (!$verificationToken) {
            // Log failed verification attempt
            if ($token = VerificationToken::where('token', $request->code)
                ->where('type', 'phone')
                ->where('contact', $request->phone)
                ->first()) {
                $token->incrementAttempts();

                if ($token->attempts >= 5) {
                    return response()->json([
                        'message' => 'Too many verification attempts. Please request a new code.'
                    ], 429);
                }
            }

            return response()->json([
                'message' => 'Invalid or expired verification code'
            ], 400);
        }

        $user = $verificationToken->user;

        if (!$user->hasVerifiedPhone()) {
            $user->markPhoneAsVerified();
        }

        $verificationToken->markAsVerified();

        // Log phone verification success
        activity('verification')
            ->causedBy($user)
            ->withProperties([
                'type' => 'phone',
                'contact' => $user->phone,
                'ip_address' => $request->ip(),
            ])
            ->log('Phone verified successfully');

        return response()->json([
            'message' => 'Phone number verified successfully'
        ]);
    }

    /**
     * Get verification status
     *
     * Get the current verification status for email and phone
     *
     * @authenticated
     *
     * @response 200 {
     *   "email_verified": true,
     *   "phone_verified": false,
     *   "email_verified_at": "2023-01-01T00:00:00.000000Z",
     *   "phone_verified_at": null
     * }
     */
    public function getVerificationStatus(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'email_verified' => $user->hasVerifiedEmail(),
            'phone_verified' => $user->hasVerifiedPhone(),
            'email_verified_at' => $user->email_verified_at,
            'phone_verified_at' => $user->phone_verified_at,
        ]);
    }
}
