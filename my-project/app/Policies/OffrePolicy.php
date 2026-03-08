<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OffrePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view the list
    }

    public function view(User $user, Offre $offre): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Entreprise]);
    }

    public function update(User $user, Offre $offre): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Entreprise && $offre->entreprise_id === $user->id;
    }

    public function delete(User $user, Offre $offre): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Entreprise && $offre->entreprise_id === $user->id;
    }
}
