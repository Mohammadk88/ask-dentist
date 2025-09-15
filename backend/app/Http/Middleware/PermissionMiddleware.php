<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();

        // Check if user has any of the required permissions
        if (!$user->hasAnyPermission($permissions)) {
            // Skip activity logging in test environments
            if (!in_array(app()->environment(), ['testing', 'dusk.local'])) {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'required_permissions' => $permissions,
                        'user_permissions' => $user->getAllPermissions()->pluck('name'),
                        'requested_route' => $request->route()?->getName(),
                        'ip_address' => $request->ip(),
                    ])
                    ->log('Unauthorized permission access attempt');
            }

            return response()->json([
                'message' => 'Insufficient permissions. Required permissions: ' . implode(', ', $permissions)
            ], 403);
        }

        return $next($request);
    }
}
