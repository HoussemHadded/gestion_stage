<?php

// app/Models/Skill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Students who have this skill.
     * Accessed via the student_skills pivot table.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_skills')
                    ->withPivot('level')
                    ->withTimestamps();
    }

    /**
     * Internship offers that require this skill.
     * Accessed via the offer_skills pivot table.
     */
    public function offres(): BelongsToMany
    {
        return $this->belongsToMany(Offre::class, 'offer_skills')
                    ->withTimestamps();
    }
}
