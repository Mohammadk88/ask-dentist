<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'custom.role' => \App\Http\Middleware\RoleMiddleware::class,
            'custom.permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'web.role' => \App\Http\Middleware\WebRoleMiddleware::class,
            'scope' => \App\Http\Middleware\ScopeMiddleware::class,
            'verified' => \App\Http\Middleware\VerifiedMiddleware::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'opt.auth' => \App\Http\Middleware\OptionalAuthenticate::class,
        ]);

        // Apply locale middleware globally to web and API routes
        $middleware->web([\App\Http\Middleware\SetLocale::class]);
        $middleware->api([\App\Http\Middleware\SetLocale::class]);

        // Apply auth:api middleware to API routes by default
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
