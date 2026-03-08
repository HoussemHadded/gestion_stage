<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;

/**
 * DashboardController — point d'entrée après connexion.
 * Redirige chaque utilisateur vers son tableau de bord selon son rôle
 * en utilisant UserRole::dashboardRoute() (Phase 3 : suppression des redirections codées en dur).
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Délégation au Enum — aucun match hardcodé ici
        return redirect()->route($user->role->dashboardRoute());
    }
}