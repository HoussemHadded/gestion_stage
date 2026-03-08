<?php

// Refactored: Laravel 12 authoritative middleware registration.
// Kernel.php removed — all middleware now lives here.
// Added: 'role' alias for RBAC, explicit web stack confirmation.

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // -------------------------------------------------------
        // Route middleware aliases
        // -------------------------------------------------------
        $middleware->alias([
            // Custom RBAC middleware — used as 'role:admin', 'role:student', etc.
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // -------------------------------------------------------
        // Throttle the authentication endpoints to prevent brute force.
        // The 'throttle:10,1' limiter is applied directly in routes/web.php
        // to the POST /login route. No global config needed here.
        // -------------------------------------------------------

        // Note: In Laravel 12 the following middleware are active by default
        // in the 'web' group via the framework's own pipeline:
        //   - EncryptCookies
        //   - AddQueuedCookiesToResponse
        //   - StartSession
        //   - ShareErrorsFromSession
        //   - VerifyCsrfToken          ← CSRF protection IS active
        //   - SubstituteBindings
        // No manual re-declaration is needed.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        \App\Providers\AuthServiceProvider::class,
    ])
    ->create();

