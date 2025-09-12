<?php

namespace App\Http\Middleware;

use App\Infrastructure\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Audit Log Middleware
 *
 * Logs sensitive actions for security and compliance purposes
 */
class AuditMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): HttpResponse
    {
        $response = $next($request);

        // Only log successful requests (2xx status codes)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request if it's a sensitive action
     */
    private function logRequest(Request $request, HttpResponse $response): void
    {
        try {
            $action = $this->determineAction($request);

            if ($action && $this->isSensitiveAction($action)) {
                $metadata = $this->gatherMetadata($request, $response);

                AuditLog::log(
                    action: $action,
                    model: null, // Model will be determined by the controller if needed
                    oldValues: [],
                    newValues: $this->extractNewValues($request),
                    metadata: $metadata
                );
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            Log::error('Audit logging failed', [
                'error' => $e->getMessage(),
                'request_url' => $request->fullUrl(),
                'user_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Determine the action based on the request
     */
    private function determineAction(Request $request): ?string
    {
        $path = $request->path();
        $method = $request->method();

        // Authentication actions
        if (str_contains($path, 'auth/login')) {
            return 'login';
        }

        if (str_contains($path, 'auth/logout')) {
            return 'logout';
        }

        if (str_contains($path, 'auth/register')) {
            return 'register';
        }

        if (str_contains($path, 'change-password') || str_contains($path, 'reset-password')) {
            return 'password_changed';
        }

        // Treatment workflow actions
        if (str_contains($path, 'treatment-plans')) {
            switch ($method) {
                case 'POST':
                    return 'treatment_plan_created';
                case 'PUT':
                case 'PATCH':
                    if (str_contains($path, 'accept')) {
                        return 'treatment_plan_accepted';
                    }
                    if (str_contains($path, 'reject')) {
                        return 'treatment_plan_rejected';
                    }
                    return 'treatment_plan_updated';
                case 'DELETE':
                    return 'treatment_plan_deleted';
            }
        }

        // File management actions
        if (str_contains($path, 'files')) {
            switch ($method) {
                case 'POST':
                    if (str_contains($path, 'upload')) {
                        return 'file_uploaded';
                    }
                    return 'file_created';
                case 'GET':
                    if (str_contains($path, 'download')) {
                        return 'file_downloaded';
                    }
                    if (str_contains($path, 'signed-url')) {
                        return 'file_url_generated';
                    }
                    return 'file_accessed';
                case 'PUT':
                case 'PATCH':
                    return 'file_updated';
                case 'DELETE':
                    return 'file_deleted';
            }
        }

        // Appointment actions
        if (str_contains($path, 'appointments')) {
            switch ($method) {
                case 'POST':
                    return 'appointment_scheduled';
                case 'PUT':
                case 'PATCH':
                    if (str_contains($path, 'cancel')) {
                        return 'appointment_cancelled';
                    }
                    return 'appointment_updated';
                case 'DELETE':
                    return 'appointment_deleted';
            }
        }

        // Profile updates
        if (str_contains($path, 'profile') && in_array($method, ['PUT', 'PATCH'])) {
            return 'profile_updated';
        }

        // Generic CRUD actions for sensitive resources
        if ($this->isSensitiveResource($path)) {
            switch ($method) {
                case 'POST':
                    return 'created';
                case 'PUT':
                case 'PATCH':
                    return 'updated';
                case 'DELETE':
                    return 'deleted';
                case 'GET':
                    return 'viewed';
            }
        }

        return null;
    }

    /**
     * Check if the action is sensitive and should be logged
     */
    private function isSensitiveAction(string $action): bool
    {
        $sensitiveActions = [
            'login',
            'logout',
            'register',
            'password_changed',
            'profile_updated',
            'treatment_plan_created',
            'treatment_plan_accepted',
            'treatment_plan_rejected',
            'treatment_plan_updated',
            'treatment_plan_deleted',
            'appointment_scheduled',
            'appointment_cancelled',
            'appointment_updated',
            'appointment_deleted',
            'file_uploaded',
            'file_downloaded',
            'file_url_generated',
            'file_accessed',
            'file_updated',
            'file_deleted',
            'created',
            'updated',
            'deleted',
        ];

        return in_array($action, $sensitiveActions);
    }

    /**
     * Check if the resource is sensitive
     */
    private function isSensitiveResource(string $path): bool
    {
        $sensitiveResources = [
            'users',
            'patients',
            'doctors',
            'clinics',
            'treatment-requests',
            'treatment-plans',
            'appointments',
            'reviews',
            'files',
            'medical-files',
        ];

        foreach ($sensitiveResources as $resource) {
            if (str_contains($path, $resource)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gather additional metadata about the request
     */
    private function gatherMetadata(Request $request, HttpResponse $response): array
    {
        return [
            'response_status' => $response->getStatusCode(),
            'request_size' => strlen($request->getContent()),
            'response_size' => strlen($response->getContent()),
            'route_name' => $request->route()?->getName(),
            'route_parameters' => $request->route()?->parameters() ?? [],
            'query_parameters' => $request->query(),
            'request_id' => $request->header('X-Request-ID'),
            'correlation_id' => $request->header('X-Correlation-ID'),
            'client_version' => $request->header('X-Client-Version'),
            'platform' => $request->header('X-Platform'),
        ];
    }

    /**
     * Extract new values from the request (excluding sensitive data)
     */
    private function extractNewValues(Request $request): array
    {
        $data = $request->all();

        // Remove sensitive fields
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'token',
            'api_token',
            'access_token',
            'refresh_token',
        ];

        foreach ($sensitiveFields as $field) {
            unset($data[$field]);
        }

        return $data;
    }
}
