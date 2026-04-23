<?php

namespace App\Enums;

enum StatutCandidature: string
{
    case EnAttente = 'en_attente';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Acceptee = 'accepte';
    case Refusee = 'refuse';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Shortlisted => 'Présélectionné',
            self::Interview => 'Entretien',
            self::Acceptee => 'Acceptée',
            self::Refusee => 'Refusée',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::EnAttente => 'warning',
            self::Shortlisted => 'info',
            self::Interview => 'primary',
            self::Acceptee => 'success',
            self::Refusee => 'danger',
        };
    }
}
