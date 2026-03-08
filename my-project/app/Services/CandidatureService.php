<?php

namespace App\Services;

use App\Enums\StatutCandidature;
use App\Models\Candidature;

/**
 * Service de gestion des candidatures.
 * Phase 3 : updateStatut() accepte désormais une instance StatutCandidature
 * (et non une chaîne brute) pour garantir la cohérence du typage fort.
 */
class CandidatureService
{
    public function __construct(
        private CacheService $cacheService
    ) {}

    public function store(array $data): Candidature
    {
        $data['date_candidature'] = now();
        $data['statut']           = StatutCandidature::EnAttente;

        $candidature = Candidature::create($data);

        $this->cacheService->forgetCandidatures();

        return $candidature;
    }

    public function update(Candidature $candidature, array $data): bool
    {
        $result = $candidature->update($data);

        $this->cacheService->forgetCandidatures();

        return $result;
    }

    /**
     * Met à jour le statut d'une candidature.
     *
     * @param  Candidature        $candidature
     * @param  StatutCandidature  $statut  — enum typé fort (plus de string brut)
     */
    public function updateStatut(Candidature $candidature, StatutCandidature $statut): bool
    {
        $result = $candidature->update(['statut' => $statut]);

        $this->cacheService->forgetCandidatures();

        return $result;
    }

    public function delete(Candidature $candidature): bool
    {
        $result = $candidature->delete();

        $this->cacheService->forgetCandidatures();

        return $result;
    }
}
