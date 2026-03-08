<?php

namespace App\Enums;

enum StatutCandidature: string
{
    case EnAttente = 'en_attente';
    case Acceptee = 'accepte';
    case Refusee = 'refuse';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Acceptee => 'Acceptée',
            self::Refusee => 'Refusée',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::EnAttente => 'warning',
            self::Acceptee => 'success',
            self::Refusee => 'danger',
        };
    }
}
