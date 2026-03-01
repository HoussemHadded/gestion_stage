<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'company_name', 'company_address'
    ];

    protected $hidden = [
        'password',
    ];

    // One Entreprise has many Offres
    public function offres()
    {
        return $this->hasMany(Offre::class, 'entreprise_id');
    }

    // One Student has many Candidatures
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'student_id');
    }
}