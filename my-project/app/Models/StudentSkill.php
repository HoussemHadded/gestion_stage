<?php

// app/Models/StudentSkill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Explicit pivot model for the student_skills table.
 *
 * Using a full Eloquent model (rather than the implicit pivot) allows us to
 * query, create, and update student skill entries directly without going
 * through the User → skills relationship every time.
 */
class StudentSkill extends Model
{
    use HasFactory;

    protected $table = 'student_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',   // beginner | intermediate | advanced | expert
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** The student who owns this skill entry. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The skill referenced by this entry. */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the numeric weight of a proficiency level (used in scoring).
     *
     * @return int  0–4
     */
    public function levelWeight(): int
    {
        return match ($this->level) {
            'expert'       => 4,
            'advanced'     => 3,
            'intermediate' => 2,
            'beginner'     => 1,
            default        => 0,
        };
    }
}
