<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();
        $token = $user->token();

        if (!$token) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Check if token has any of the required scopes
        $tokenScopes = $token->scopes ?? [];
        $hasRequiredScope = !empty(array_intersect($scopes, $tokenScopes));

        if (!$hasRequiredScope) {
            activity('security')
                ->causedBy($user)
                ->withProperties([
                    'required_scopes' => $scopes,
                    'token_scopes' => $tokenScopes,
                    'requested_route' => $request->route()?->getName(),
                    'ip_address' => $request->ip(),
                ])
                ->log('Unauthorized scope access attempt');

            return response()->json([
                'message' => 'Insufficient token permissions. Required scopes: ' . implode(', ', $scopes)
            ], 403);
        }

        return $next($request);
    }
}
