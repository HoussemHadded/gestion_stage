<?php

// app/Models/OffreMatch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Stores the AI matching result between one student and one internship offer.
 *
 * Named OffreMatch (not Match) to avoid conflict with PHP's reserved keyword.
 *
 * The `details` JSON column holds the full breakdown:
 * {
 *   "skills"      : { "score": 40, "matched": ["PHP","Laravel"], "missing": ["Docker"] },
 *   "level"       : { "score": 15, "reason": "Bac+3 corresponds to level_required" },
 *   "location"    : { "score": 8,  "reason": "Same city: Tunis" },
 *   "preferences" : { "score": 7,  "reason": "Type 'stage PFE' matches preference" },
 *   "projects"    : { "score": 6,  "reason": "CV mentions web projects" },
 *   "ai_summary"  : "This student is a strong match because …"
 * }
 */
class OffreMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'student_id',
        'offre_id',
        'score',
        'details',
    ];

    protected $casts = [
        'score'   => 'decimal:2',
        'details' => 'array',   // auto encode/decode JSON
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** The student (User) this match belongs to. */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** The internship offer this match belongs to. */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'offre_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Returns a CSS colour class based on the score value.
     *
     * ≥ 70  → green   (good match)
     * ≥ 40  → orange  (partial match)
     * < 40  → red     (weak match)
     */
    public function badgeColor(): string
    {
        return match (true) {
            $this->score >= 70 => 'success',
            $this->score >= 40 => 'warning',
            default            => 'danger',
        };
    }

    /**
     * Human-readable label for the score band.
     */
    public function matchLabel(): string
    {
        return match (true) {
            $this->score >= 70 => 'Excellent match',
            $this->score >= 40 => 'Match partiel',
            default            => 'Faible compatibilité',
        };
    }
}
