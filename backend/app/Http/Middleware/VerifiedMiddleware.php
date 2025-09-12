<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $verificationType = 'email'): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();

        // Check verification based on type
        $isVerified = match ($verificationType) {
            'email' => $user->hasVerifiedEmail(),
            'phone' => $user->hasVerifiedPhone(),
            'both' => $user->hasVerifiedEmail() && $user->hasVerifiedPhone(),
            'either' => $user->hasVerifiedEmail() || $user->hasVerifiedPhone(),
            default => false,
        };

        if (!$isVerified) {
            $message = match ($verificationType) {
                'email' => 'Email verification required to access this resource',
                'phone' => 'Phone verification required to access this resource',
                'both' => 'Both email and phone verification required to access this resource',
                'either' => 'Email or phone verification required to access this resource',
                default => 'Verification required to access this resource',
            };

            activity('security')
                ->causedBy($user)
                ->withProperties([
                    'verification_type' => $verificationType,
                    'email_verified' => $user->hasVerifiedEmail(),
                    'phone_verified' => $user->hasVerifiedPhone(),
                    'requested_route' => $request->route()?->getName(),
                    'ip_address' => $request->ip(),
                ])
                ->log('Access denied - verification required');

            return response()->json([
                'message' => $message,
                'verification_status' => [
                    'email_verified' => $user->hasVerifiedEmail(),
                    'phone_verified' => $user->hasVerifiedPhone(),
                ]
            ], 403);
        }

        return $next($request);
    }
}
