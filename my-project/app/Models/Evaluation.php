<?php

// Phase 4 — Gestion de Stage

namespace App\Models;

use App\Enums\EvaluationStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'student_id',
        'encadrant_id',
        'offre_id',
        'note',
        'commentaire',
        'date_evaluation',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'date_evaluation' => 'date',
        'note'            => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** The student who was evaluated. */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** The supervisor (encadrant) who gave the evaluation. */
    public function encadrant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'encadrant_id');
    }

    /** The internship offer this evaluation is attached to. */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'offre_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the EvaluationStatut enum case that corresponds to this
     * evaluation's numeric note (0–20).
     */
    public function gradeBand(): EvaluationStatut
    {
        return EvaluationStatut::fromNote($this->note);
    }
}
