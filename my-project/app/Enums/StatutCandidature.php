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

    public function colorClass(): string
    {
        return match ($this) {
            self::EnAttente => 'bg-yellow-100 text-yellow-800',
            self::Shortlisted => 'bg-blue-100 text-blue-800',
            self::Interview => 'bg-purple-100 text-purple-800',
            self::Acceptee => 'bg-green-100 text-green-800',
            self::Refusee => 'bg-red-100 text-red-800',
        };
    }
}
