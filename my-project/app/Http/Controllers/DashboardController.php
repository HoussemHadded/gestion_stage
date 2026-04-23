<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Candidature;
use App\Models\Offre;
use App\Services\DashboardService;

/**
 * DashboardController — Point d'entrée unique après connexion.
 *
 * Responsabilité : détecter le rôle de l'utilisateur authentifié,
 * charger les données spécifiques à ce rôle et retourner la vue dédiée.
 * Aucune logique mixte, aucune duplication entre rôles.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return match ($user->role) {
            UserRole::Admin      => $this->adminDashboard(),
            UserRole::Etudiant   => $this->etudiantDashboard($user),
            UserRole::Entreprise => $this->entrepriseDashboard($user),
        };
    }

    // ─────────────────────────────────────────────────────────────────
    //  Private helpers — each role gets its own isolated method
    // ─────────────────────────────────────────────────────────────────

    private function adminDashboard()
    {
        $data = $this->dashboardService->getAdminStats();

        return view('dashboard.admin', $data);
    }

    private function etudiantDashboard(\App\Models\User $user)
    {
        $stats = $this->dashboardService->getStudentStats($user);

        $candidatures = Candidature::with(['offre.entreprise'])
            ->where('student_id', $user->id)
            ->latest('date_candidature')
            ->take(5)
            ->get();

        return view('dashboard.etudiant', array_merge($stats, compact('candidatures')));
    }

    private function entrepriseDashboard(\App\Models\User $user)
    {
        $stats = $this->dashboardService->getEntrepriseStats($user);

        $offres = Offre::where('entreprise_id', $user->id)
            ->withCount('candidatures')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.entreprise', array_merge($stats, compact('offres')));
    }
}