<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin     = 'admin';
    case Student   = 'student';
    case Entreprise = 'entreprise';
    case Encadrant = 'encadrant';

    /** Libellé affiché dans les vues */
    public function label(): string
    {
        return match ($this) {
            self::Admin      => 'Administrateur',
            self::Student    => 'Étudiant',
            self::Entreprise => 'Entreprise',
            self::Encadrant  => 'Encadrant (Superviseur)',
        };
    }

    /**
     * Retourne le nom de route nommée du tableau de bord pour ce rôle.
     * Correction Phase 3: Student → student.dashboard, Entreprise → entreprise.dashboard.
     */
    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin      => 'admin.dashboard',
            self::Student    => 'student.dashboard',
            self::Entreprise => 'entreprise.dashboard',
            self::Encadrant  => 'encadrant.dashboard',
        };
    }
}
