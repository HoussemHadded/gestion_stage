<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'offre_id', 'cv', 'statut', 'date_candidature'
    ];

    // A Candidature belongs to one Student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // A Candidature belongs to one Offre
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}