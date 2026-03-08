<?php

// Phase 4 — Gestion de Stage

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'company_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => UserRole::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** One Entreprise has many Offres */
    public function offres()
    {
        return $this->hasMany(Offre::class, 'entreprise_id');
    }

    /** One Student has many Candidatures */
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'student_id');
    }

    /**
     * Evaluations received by this user (as a student).
     * Phase 4
     */
    public function receivedEvaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'student_id');
    }

    /**
     * Evaluations given by this user (as an encadrant / supervisor).
     * Phase 4
     */
    public function givenEvaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'encadrant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers (compare against UserRole enum instances)
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student;
    }

    public function isEntreprise(): bool
    {
        return $this->role === UserRole::Entreprise;
    }

    public function isEncadrant(): bool
    {
        return $this->role === UserRole::Encadrant;
    }
}