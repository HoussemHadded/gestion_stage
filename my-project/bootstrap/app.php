<?php

// bootstrap/app.php

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
        // Alias de middleware pour les routes
        // -------------------------------------------------------
        $middleware->alias([
            // RBAC : utilisé comme 'role:admin', 'role:student', 'role:entreprise'
            'role'      => \App\Http\Middleware\RoleMiddleware::class,
            // Redirige les utilisateurs déjà connectés hors de /login et /register
            'auth.guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        \App\Providers\AuthServiceProvider::class,
    ])
    ->create();
