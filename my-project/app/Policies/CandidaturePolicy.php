<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Candidature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CandidaturePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Entreprise, UserRole::Student]);
    }

    public function view(User $user, Candidature $candidature): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role === UserRole::Student) {
            return $candidature->student_id === $user->id;
        }

        if ($user->role === UserRole::Entreprise) {
            return $candidature->offre->entreprise_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Student;
    }

    public function update(User $user, Candidature $candidature): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Candidature $candidature): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function updateStatut(User $user, Candidature $candidature): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Entreprise && $candidature->offre->entreprise_id === $user->id;
    }
}
