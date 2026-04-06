<?php

// app/Models/Offre.php


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
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entreprise_id');
    }

    // One Offre has many Candidatures
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}