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

    /**
     * Invalide le cache après create/update/destroy d'une offre.
     */
    public static function forgetOffres(): void
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
    public static function forgetUsers(): void
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
    public static function forgetCandidatures(): void
    {
        for ($i = 1; $i <= self::MAX_PAGES; $i++) {
            foreach (self::STATUTS as $statut) {
                Cache::forget("candidatures_list_page_{$i}_statut_{$statut}");
            }
        }
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Invalide uniquement les listes de formulaires (entreprises, offres, students).
     */
    public static function forgetFormLists(): void
    {
        Cache::forget('entreprises_list');
        Cache::forget('offres_all_list');
        Cache::forget('students_list');
    }

    /**
     * Invalide le cache du dashboard admin.
     */
    public static function forgetAdminDashboard(): void
    {
        Cache::forget('admin_dashboard_stats');
    }
}
