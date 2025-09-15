<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebRoleMiddleware
{
    /**
     * Handle an incoming request for web routes with role checking.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            // Log unauthorized access attempt
            activity('security')
                ->causedBy($user)
                ->withProperties([
                    'required_roles' => $roles,
                    'user_role' => $user->role,
                    'requested_route' => $request->route()?->getName(),
                    'ip_address' => $request->ip(),
                ])
                ->log('Unauthorized role access attempt');

            // Redirect based on user's actual role
            if ($user->role === 'doctor') {
                return redirect('/doctor')->with('error', 'Access denied. You do not have admin privileges.');
            }

            if ($user->role === 'patient') {
                return redirect('/')->with('error', 'Access denied. Patients cannot access this area.');
            }

            if ($user->role === 'clinic_manager') {
                return redirect('/clinic')->with('error', 'Access denied. Clinic managers cannot access this area.');
            }

            // Fallback for users without clear roles
            return redirect()->route('login')->with('error', 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
