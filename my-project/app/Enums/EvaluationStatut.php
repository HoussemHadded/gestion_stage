<?php

// Phase 4 — Gestion de Stage

namespace App\Enums;

enum EvaluationStatut: string
{
    case Insuffisant = 'insuffisant';
    case Passable    = 'passable';
    case AssezBien   = 'assez_bien';
    case Bien        = 'bien';
    case TresBien    = 'tres_bien';
    case Excellent   = 'excellent';

    /*
    |--------------------------------------------------------------------------
    | Factory
    |--------------------------------------------------------------------------
    */

    /**
     * Derive the evaluation band from a numeric grade (0–20).
     *
     * Scale:
     *   0–7   → Insuffisant
     *   8–9   → Passable
     *   10–11 → Assez bien
     *   12–13 → Bien
     *   14–15 → Très bien
     *   16–20 → Excellent
     */
    public static function fromNote(int $note): self
    {
        return match (true) {
            $note >= 16 => self::Excellent,
            $note >= 14 => self::TresBien,
            $note >= 12 => self::Bien,
            $note >= 10 => self::AssezBien,
            $note >= 8  => self::Passable,
            default     => self::Insuffisant,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /** Human-readable French label. */
    public function label(): string
    {
        return match ($this) {
            self::Insuffisant => 'Insuffisant',
            self::Passable    => 'Passable',
            self::AssezBien   => 'Assez bien',
            self::Bien        => 'Bien',
            self::TresBien    => 'Très bien',
            self::Excellent   => 'Excellent',
        };
    }

    /** Bootstrap badge CSS class for display in Blade views. */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Insuffisant => 'badge bg-danger',
            self::Passable    => 'badge bg-warning text-dark',
            self::AssezBien   => 'badge bg-info text-dark',
            self::Bien        => 'badge bg-primary',
            self::TresBien    => 'badge bg-success',
            self::Excellent   => 'badge bg-success fw-bold',
        };
    }
}
