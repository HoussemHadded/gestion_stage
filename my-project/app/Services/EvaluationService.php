<?php

// app/Services/EvaluationService.php
// Phase 4 — Gestion de Stage

namespace App\Services;

use App\Enums\EvaluationStatut;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service de gestion des évaluations de stage.
 *
 * Responsabilités :
 *   - Création, mise à jour, suppression d'évaluations
 *   - Récupération filtrée selon le rôle de l'utilisateur
 */
class EvaluationService
{
    /*
    |--------------------------------------------------------------------------
    | Write operations
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une nouvelle évaluation et dérive automatiquement le statut
     * (grade band) à partir de la note.
     *
     * @param  int    $encadrantId  ID de l'encadrant authentifié
     * @param  array  $data         Données validées par StoreEvaluationRequest
     * @return Evaluation
     */
    public function store(int $encadrantId, array $data): Evaluation
    {
        $data['encadrant_id'] = $encadrantId;

        return Evaluation::create($data);
    }

    /**
     * Met à jour partiellement une évaluation existante.
     *
     * @param  Evaluation  $evaluation
     * @param  array       $data  Données validées par UpdateEvaluationRequest
     * @return bool
     */
    public function update(Evaluation $evaluation, array $data): bool
    {
        return $evaluation->update($data);
    }

    /**
     * Supprime une évaluation.
     *
     * @param  Evaluation  $evaluation
     * @return bool|null
     */
    public function delete(Evaluation $evaluation): bool|null
    {
        return $evaluation->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Read operations
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne toutes les évaluations créées par un encadrant donné,
     * avec les relations étudiant et offre chargées en eager loading.
     *
     * @param  int  $encadrantId
     * @return Collection<int, Evaluation>
     */
    public function getForEncadrant(int $encadrantId): Collection
    {
        return Evaluation::with(['student', 'offre'])
            ->where('encadrant_id', $encadrantId)
            ->latest('date_evaluation')
            ->get();
    }

    /**
     * Retourne toutes les évaluations reçues par un étudiant donné,
     * avec les relations encadrant et offre chargées en eager loading.
     *
     * @param  int  $studentId
     * @return Collection<int, Evaluation>
     */
    public function getForStudent(int $studentId): Collection
    {
        return Evaluation::with(['encadrant', 'offre'])
            ->where('student_id', $studentId)
            ->latest('date_evaluation')
            ->get();
    }
}
