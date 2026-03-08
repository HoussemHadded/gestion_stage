<?php

// app/Policies/EvaluationPolicy.php
// Phase 4 — Gestion de Stage

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Evaluation;
use App\Models\User;

/**
 * Policy d'autorisation pour le modèle Evaluation.
 *
 * viewAny → admin ou encadrant
 * view    → admin OU encadrant propriétaire OU étudiant concerné
 * create  → encadrant uniquement
 * update  → encadrant propriétaire ou admin
 * delete  → admin uniquement
 */
class EvaluationPolicy
{
    /**
     * Tout admin ou encadrant peut voir la liste des évaluations.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Encadrant]);
    }

    /**
     * Un admin peut tout voir.
     * L'encadrant propriétaire peut voir ses évaluations.
     * L'étudiant concerné peut consulter sa propre évaluation.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role === UserRole::Encadrant) {
            return $evaluation->encadrant_id === $user->id;
        }

        if ($user->role === UserRole::Student) {
            return $evaluation->student_id === $user->id;
        }

        return false;
    }

    /**
     * Seul un encadrant peut créer une évaluation.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Encadrant;
    }

    /**
     * L'encadrant propriétaire ou un admin peut modifier une évaluation.
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Encadrant && $evaluation->encadrant_id === $user->id;
    }

    /**
     * Seul un admin peut supprimer une évaluation.
     */
    public function delete(User $user, Evaluation $evaluation): bool
    {
        return $user->role === UserRole::Admin;
    }
}
