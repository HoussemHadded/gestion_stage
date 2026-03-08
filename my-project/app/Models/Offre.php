<?php

// Phase 4 — Gestion de Stage

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_publication',
        'entreprise_id'
    ];

    protected $casts = [
        'date_publication' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // An Offre belongs to one Entreprise
    public function entreprise()
    {
        return $this->belongsTo(User::class, 'entreprise_id');
    }

    // One Offre has many Candidatures
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * One Offre has many Evaluations.
     * Phase 4
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'offre_id');
    }
}