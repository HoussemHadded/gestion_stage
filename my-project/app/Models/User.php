<?php

// app/Models/User.php

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
        'cv_text',        // Raw CV text for NLP skill extraction
        'cv_score',       // AI Evaluation
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role'              => UserRole::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** One Entreprise has many Offres */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'entreprise_id');
    }

    /** One Student has many Candidatures */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'student_id');
    }

    /**
     * Skills belonging to this student (via student_skills pivot).
     * Each pivot row carries a `level` field (beginner→expert).
     */
    public function skills(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'student_skills')
                    ->withPivot('level')
                    ->withTimestamps();
    }

    /**
     * AI match results stored for this student.
     */
    public function matches(): HasMany
    {
        return $this->hasMany(OffreMatch::class, 'student_id');
    }

    /**
     * Les offres favorites/sauvegardées par l'étudiant.
     */
    public function savedOffres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Offre::class, 'saved_offres', 'user_id', 'offre_id')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isEtudiant(): bool
    {
        return $this->role === UserRole::Etudiant;
    }

    /** Alias backward-compatibility */
    public function isStudent(): bool
    {
        return $this->isEtudiant();
    }

    public function isEntreprise(): bool
    {
        return $this->role === UserRole::Entreprise;
    }


}