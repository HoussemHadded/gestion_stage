<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        
        if (!$user || !in_array($user->role, $roles)) {
            abort(403, "Accès interdit : Vous n'avez pas les droits nécessaires.");
        }
        
        return $next($request);
    }
}