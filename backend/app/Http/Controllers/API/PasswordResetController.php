<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

/**
 * @group Password Reset
 *
 * Password reset functionality for user account recovery
 */
class PasswordResetController extends Controller
{
    /**
     * Send password reset link
     *
     * Send a password reset link to the user's email address
     *
     * @bodyParam email string required The user's email address. Example: john@example.com
     *
     * @response 200 {
     *   "message": "Password reset link sent to your email address"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "email": ["The email field is required."]
     *   }
     * }
     *
     * @response 404 {
     *   "message": "We couldn't find a user with that email address"
     * }
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'We couldn\'t find a user with that email address'
            ], 404);
        }

        // Send password reset notification
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // Log password reset request
            activity('authentication')
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Password reset link requested');

            return response()->json([
                'message' => 'Password reset link sent to your email address'
            ]);
        }

        return response()->json([
            'message' => 'Unable to send password reset link'
        ], 500);
    }

    /**
     * Reset password
     *
     * Reset the user's password using the reset token
     *
     * @bodyParam token string required The password reset token. Example: abc123def456
     * @bodyParam email string required The user's email address. Example: john@example.com
     * @bodyParam password string required New password (min 8 characters). Example: NewSecurePass123
     * @bodyParam password_confirmation string required Password confirmation. Example: NewSecurePass123
     *
     * @response 200 {
     *   "message": "Password has been reset successfully"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "password": ["The password confirmation does not match."]
     *   }
     * }
     *
     * @response 400 {
     *   "message": "Invalid or expired password reset token"
     * }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Revoke all existing tokens
                $user->tokens()->delete();

                event(new PasswordReset($user));

                // Log password reset success
                activity('authentication')
                    ->causedBy($user)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log('Password reset successfully');
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully'
            ]);
        }

        $errorMessage = match ($status) {
            Password::INVALID_TOKEN => 'Invalid or expired password reset token',
            Password::INVALID_USER => 'We couldn\'t find a user with that email address',
            default => 'Unable to reset password'
        };

        return response()->json([
            'message' => $errorMessage
        ], 400);
    }

    /**
     * Change password for authenticated user
     *
     * Change the current user's password
     *
     * @authenticated
     *
     * @bodyParam current_password string required Current password. Example: OldPassword123
     * @bodyParam password string required New password (min 8 characters). Example: NewSecurePass123
     * @bodyParam password_confirmation string required Password confirmation. Example: NewSecurePass123
     *
     * @response 200 {
     *   "message": "Password changed successfully"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "current_password": ["The current password is incorrect."]
     *   }
     * }
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Revoke all tokens except current one
        $currentToken = $user->token();
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        // Log password change
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Password changed successfully');

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
