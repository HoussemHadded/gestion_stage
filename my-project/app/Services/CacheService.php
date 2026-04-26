<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Service d'invalidation ciblée du cache (Phase 9).
 * Remplace Cache::flush() par des oublis spécifiques.
 */
class CacheService
{
    private const MAX_PAGES = 50;

    private const STATUTS = ['all', 'en_attente', 'accepte', 'refuse'];

    /** Max pages expected per student — generous upper bound */
    private const MAX_STUDENT_PAGES = 20;

    /**
     * Invalide le cache après create/update/destroy d'une offre.
     */
    public function forgetOffres(): void
    {
        for ($i = 1; $i <= self::MAX_PAGES; $i++) {
            Cache::forget("offres_list_page_{$i}");
        }
        Cache::forget('offres_all_list');
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Invalide le cache après create/update/destroy d'un utilisateur.
     */
    public function forgetUsers(): void
    {
        for ($i = 1; $i <= self::MAX_PAGES; $i++) {
            Cache::forget("users_list_page_{$i}");
        }
        Cache::forget('entreprises_list');
        Cache::forget('students_list');
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Invalide le cache après create/update/destroy/updateStatut d'une candidature.
     */
    public function forgetCandidatures(): void
    {
        // Company/admin paginated lists
        for ($i = 1; $i <= self::MAX_PAGES; $i++) {
            foreach (self::STATUTS as $statut) {
                Cache::forget("candidatures_list_page_{$i}_statut_{$statut}");
            }
        }
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * ✅ FIX: Invalidate per-student candidature cache.
     * Must be called after every store/delete of a candidature.
     * Without this, "Mes Candidatures" shows stale data from the first application.
     */
    public function forgetStudentCandidatures(int $studentId): void
    {
        for ($i = 1; $i <= self::MAX_STUDENT_PAGES; $i++) {
            Cache::forget("student_candidatures_{$studentId}_page_{$i}");
        }
    }

    /**
     * Invalide uniquement les listes de formulaires (entreprises, offres, students).
     */
    public function forgetFormLists(): void
    {
        Cache::forget('entreprises_list');
        Cache::forget('offres_all_list');
        Cache::forget('students_list');
    }

    /**
     * Invalide le cache du dashboard admin.
     */
    public function forgetAdminDashboard(): void
    {
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * ✅ FIX: Invalide le cache du dashboard entreprise pour une société donnée.
     * Doit être appelé après chaque create/update de candidature liée à cette entreprise.
     * Sans cela, le dashboard reste figé pendant 5 minutes après chaque nouvelle candidature.
     */
    public function forgetEntrepriseDashboard(int $entrepriseId): void
    {
        Cache::forget("entreprise_dashboard_stats_{$entrepriseId}");
    }

    /**
     * ✅ FIX: Invalide le cache du dashboard étudiant pour un étudiant donné.
     * Doit être appelé après chaque create/update/delete de candidature de cet étudiant.
     */
    public function forgetStudentDashboard(int $studentId): void
    {
        Cache::forget("student_dashboard_stats_{$studentId}");
    }
}
