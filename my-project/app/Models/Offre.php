<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'date_publication', 'entreprise_id'
    ];

    // An Offre belongs to one Entreprise
    public function entreprise()
    {
        return $this->belongsTo(User::class, 'entreprise_id');
    }

    // One Offre has many Candidatures
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}