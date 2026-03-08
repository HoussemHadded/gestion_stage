<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * RoleMiddleware — vérifie que l'utilisateur connecté possède l'un des rôles autorisés.
 *
 * Correction Phase 3: compare `$user->role->value` (string) au lieu de l'objet enum,
 * car les paramètres de route sont toujours des chaînes de caractères.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = auth()->user();

        // role est casté en UserRole enum — on compare sa valeur string
        if (! $user || ! in_array($user->role->value, $roles, true)) {
            abort(403, "Accès interdit : Vous n'avez pas les droits nécessaires.");
        }

        return $next($request);
    }
}