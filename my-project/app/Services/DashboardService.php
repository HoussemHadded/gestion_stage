<?php

// app/Services/DashboardService.php

namespace App\Services;

use App\Enums\StatutCandidature;
use App\Enums\UserRole;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Service centralisé pour les statistiques du tableau de bord admin.
 * Toutes les données sont mises en cache 5 minutes.
 */
class DashboardService
{
    private const CACHE_KEY = 'admin_dashboard_stats';
    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Retourne les statistiques pour le dashboard admin (3 rôles uniquement).
     */
    public function getAdminStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            // ── Utilisateurs ──────────────────────────────────────────────
            $total_users       = User::count();
            $total_students    = User::where('role', UserRole::Student)->count();
            $total_entreprises = User::where('role', UserRole::Entreprise)->count();

            // ── Offres ────────────────────────────────────────────────────
            $total_offres = Offre::count();

            // ── Candidatures ──────────────────────────────────────────────
            $total_candidatures    = Candidature::count();
            $accepted_candidatures = Candidature::where('statut', StatutCandidature::Acceptee->value)->count();
            $rejected_candidatures = Candidature::where('statut', StatutCandidature::Refusee->value)->count();
            $pending_candidatures  = Candidature::where('statut', StatutCandidature::EnAttente->value)->count();

            // ── Données récentes ──────────────────────────────────────────
            $recent_candidatures = Candidature::with(['student', 'offre'])
                ->latest()
                ->take(5)
                ->get();

            $recent_users = User::latest()->take(5)->get();

            // ── Chart.js — répartition candidatures par statut ────────────
            $candidaturesByStatut = Candidature::selectRaw('statut, count(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut')
                ->toArray();

            $chartLabels = [];
            $chartData   = [];

            foreach (StatutCandidature::cases() as $statut) {
                $chartLabels[] = $statut->label();
                $chartData[]   = $candidaturesByStatut[$statut->value] ?? 0;
            }

            return compact(
                'total_users',
                'total_students',
                'total_entreprises',
                'total_offres',
                'total_candidatures',
                'accepted_candidatures',
                'rejected_candidatures',
                'pending_candidatures',
                'recent_candidatures',
                'recent_users',
                'chartLabels',
                'chartData'
            );
        });
    }

    public function getEntrepriseStats(User $user): array
    {
        return Cache::remember("entreprise_dashboard_stats_{$user->id}", self::CACHE_TTL, function () use ($user) {
            $total_offres = Offre::where('entreprise_id', $user->id)->count();

            // All candidatures for their offers
            $candidatures = Candidature::whereHas('offre', function($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            });

            $total_candidatures = (clone $candidatures)->count();
            
            $pending = (clone $candidatures)->where('statut', StatutCandidature::EnAttente->value)->count();
            $accepted = (clone $candidatures)->where('statut', StatutCandidature::Acceptee->value)->count();
            $rejected = (clone $candidatures)->where('statut', StatutCandidature::Refusee->value)->count();

            $chartLabels = ['En Attente', 'Acceptées', 'Refusées'];
            $chartData = [$pending, $accepted, $rejected];

            return compact('total_offres', 'total_candidatures', 'pending', 'accepted', 'rejected', 'chartLabels', 'chartData');
        });
    }

    public function getStudentStats(User $user): array
    {
        return Cache::remember("student_dashboard_stats_{$user->id}", self::CACHE_TTL, function () use ($user) {
            $candidatures = Candidature::where('student_id', $user->id);

            $total_candidatures = (clone $candidatures)->count();
            $pending = (clone $candidatures)->where('statut', StatutCandidature::EnAttente->value)->count();
            $accepted = (clone $candidatures)->where('statut', StatutCandidature::Acceptee->value)->count();
            $rejected = (clone $candidatures)->where('statut', StatutCandidature::Refusee->value)->count();

            $chartLabels = ['En Attente', 'Acceptées', 'Refusées'];
            $chartData = [$pending, $accepted, $rejected];

            return compact('total_candidatures', 'pending', 'accepted', 'rejected', 'chartLabels', 'chartData');
        });
    }
}
