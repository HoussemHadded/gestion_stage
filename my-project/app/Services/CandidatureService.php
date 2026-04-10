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

        // Notify the enterprise owner
        $candidature->load('offre.entreprise', 'student');
        if ($candidature->offre->entreprise) {
            try {
                \Illuminate\Support\Facades\Mail::to($candidature->offre->entreprise)->send(new \App\Mail\ApplicationSubmittedMail($candidature));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send application email: " . $e->getMessage());
            }
        }

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
     * @param  StatutCandidature  $statut
     */
    public function updateStatut(Candidature $candidature, StatutCandidature $statut): bool
    {
        $result = $candidature->update(['statut' => $statut]);

        // Notify the student
        $candidature->load('student', 'offre.entreprise');
        if ($candidature->student) {
            try {
                \Illuminate\Support\Facades\Mail::to($candidature->student)->send(new \App\Mail\ApplicationStatusUpdatedMail($candidature));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send status update email: " . $e->getMessage());
            }
        }

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
