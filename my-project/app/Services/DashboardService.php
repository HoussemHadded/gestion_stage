<?php

// app/Services/DashboardService.php
// Phase 4 — Gestion de Stage

namespace App\Services;

use App\Enums\StatutCandidature;
use App\Enums\UserRole;
use App\Models\Candidature;
use App\Models\Evaluation;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Service centralisé pour les statistiques du tableau de bord admin.
 * Toutes les données sont mises en cache 5 minutes (300 secondes).
 */
class DashboardService
{
    private const CACHE_KEY = 'admin_dashboard_stats';
    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Retourne les statistiques complètes pour le dashboard admin.
     *
     * @return array{
     *   total_users: int,
     *   total_students: int,
     *   total_entreprises: int,
     *   total_encadrants: int,
     *   total_offres: int,
     *   total_candidatures: int,
     *   accepted_candidatures: int,
     *   rejected_candidatures: int,
     *   pending_candidatures: int,
     *   total_evaluations: int,
     *   recent_candidatures: \Illuminate\Database\Eloquent\Collection,
     *   recent_users: \Illuminate\Database\Eloquent\Collection,
     *   chartLabels: array,
     *   chartData: array,
     * }
     */
    public function getAdminStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            // ── Utilisateurs ──────────────────────────────────────────────
            $total_users       = User::count();
            $total_students    = User::where('role', UserRole::Student)->count();
            $total_entreprises = User::where('role', UserRole::Entreprise)->count();
            $total_encadrants  = User::where('role', UserRole::Encadrant)->count();

            // ── Offres ────────────────────────────────────────────────────
            $total_offres = Offre::count();

            // ── Candidatures ──────────────────────────────────────────────
            $total_candidatures    = Candidature::count();
            $accepted_candidatures = Candidature::where('statut', StatutCandidature::Acceptee->value)->count();
            $rejected_candidatures = Candidature::where('statut', StatutCandidature::Refusee->value)->count();
            $pending_candidatures  = Candidature::where('statut', StatutCandidature::EnAttente->value)->count();

            // ── Évaluations ───────────────────────────────────────────────
            $total_evaluations = Evaluation::count();

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
                'total_encadrants',
                'total_offres',
                'total_candidatures',
                'accepted_candidatures',
                'rejected_candidatures',
                'pending_candidatures',
                'total_evaluations',
                'recent_candidatures',
                'recent_users',
                'chartLabels',
                'chartData'
            );
        });
    }
}
