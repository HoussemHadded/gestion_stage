<?php

// app/Models/Offre.php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'type',            // internship type e.g. "stage PFE", "alternance"
        'level_required',  // required academic level e.g. "Bac+3", "Master"
        'date_publication',
        'entreprise_id',
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
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entreprise_id');
    }

    // One Offre has many Candidatures
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Skills required by this offer (via offer_skills pivot).
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'offer_skills')
                    ->withTimestamps();
    }

    /**
     * AI match results calculated against this offer.
     */
    public function matches(): HasMany
    {
        return $this->hasMany(OffreMatch::class, 'offre_id');
    }
}