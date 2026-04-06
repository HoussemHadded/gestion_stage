<?php

// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * RoleMiddleware — vérifie que l'utilisateur connecté possède l'un des rôles autorisés.
 * Si non autorisé, redirige vers son propre dashboard avec un message d'erreur.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Compare la valeur string de l'enum avec les rôles autorisés
        if (! in_array($user->role->value, $roles, true)) {
            return redirect()
                ->route($user->role->dashboardRoute())
                ->with('error', 'Accès refusé : vous n\'avez pas les droits pour cette page.');
        }

        return $next($request);
    }
}