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
            $total_students    = User::where('role', UserRole::Etudiant)->count();
            $total_entreprises = User::where('role', UserRole::Entreprise)->count();

            // ── Offres ────────────────────────────────────────────────────
            $total_offres = Offre::count();

            // ── Candidatures ──────────────────────────────────────────────
            $total_candidatures    = Candidature::count();
            $accepted_candidatures = Candidature::where('statut', StatutCandidature::Acceptee)->count();
            $rejected_candidatures = Candidature::where('statut', StatutCandidature::Refusee)->count();
            $pending_candidatures  = Candidature::where('statut', StatutCandidature::EnAttente)->count();

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

            // ✅ All candidatures for this company's offers — filtered by entreprise_id
            $baseQuery = Candidature::whereHas('offre', function ($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            });

            $total_candidatures = (clone $baseQuery)->count();
            $pending     = (clone $baseQuery)->where('statut', StatutCandidature::EnAttente)->count();
            $shortlisted = (clone $baseQuery)->where('statut', StatutCandidature::Shortlisted)->count();
            $interview   = (clone $baseQuery)->where('statut', StatutCandidature::Interview)->count();
            $accepted    = (clone $baseQuery)->where('statut', StatutCandidature::Acceptee)->count();
            $rejected    = (clone $baseQuery)->where('statut', StatutCandidature::Refusee)->count();

            $conversion_rate = $total_candidatures > 0
                ? round(($accepted / $total_candidatures) * 100, 1)
                : 0;

            $chartLabels = ['En Attente', 'Présélection', 'Entretien', 'Embauché', 'Refusé'];
            $chartData   = [$pending, $shortlisted, $interview, $accepted, $rejected];

            // Top offers by number of candidatures received
            $top_offres = Offre::where('entreprise_id', $user->id)
                ->withCount('candidatures')
                ->orderBy('candidatures_count', 'desc')
                ->take(5)
                ->get();

            return compact(
                'total_offres', 'total_candidatures',
                'pending', 'shortlisted', 'interview', 'accepted', 'rejected',
                'conversion_rate', 'chartLabels', 'chartData', 'top_offres'
            );
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

            // AI Features Stats
            $matches = \App\Models\OffreMatch::where('student_id', $user->id);
            $total_matches = (clone $matches)->count();
            $average_score = $total_matches > 0 ? (clone $matches)->avg('score') : 0;
            $best_score = $total_matches > 0 ? (clone $matches)->max('score') : 0;
            
            // Optimization metric: count candidatures with optimized CV vs total
            $optimized_count = (clone $candidatures)->where('cv_version', 'optimized')->count();
            $optimization_rate = $total_candidatures > 0 ? ($optimized_count / $total_candidatures) * 100 : 0;

            return compact(
                'total_candidatures', 'pending', 'accepted', 'rejected', 'chartLabels', 'chartData',
                'total_matches', 'average_score', 'best_score', 'optimization_rate', 'optimized_count'
            );
        });
    }
}
