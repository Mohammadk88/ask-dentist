<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Guards\TokenGuard;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthenticate
{
    /**
     * Handle an incoming request.
     * 
     * If Authorization Bearer token exists and is valid via Passport, set auth()->user().
     * Otherwise, continue as guest without throwing exceptions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if Authorization header exists
        $authHeader = $request->header('Authorization');
        
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            try {
                // Extract token from header
                $token = substr($authHeader, 7);
                
                if (!empty($token)) {
                    // Try to authenticate using Passport
                    $guard = Auth::guard('api');
                    
                    if ($guard instanceof TokenGuard) {
                        // Set the token on the request for Passport to process
                        $request->headers->set('Authorization', $authHeader);
                        
                        // Attempt authentication without throwing exceptions
                        $user = $guard->user();
                        
                        if ($user) {
                            // Successfully authenticated
                            Auth::setUser($user);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Token is invalid or expired, continue as guest
                // Log the error for debugging if needed
                \Log::debug('Optional authentication failed: ' . $e->getMessage());
            }
        }
        
        // Set guest status as request attribute
        $request->attributes->set('is_guest', Auth::guest());
        
        return $next($request);
    }
}