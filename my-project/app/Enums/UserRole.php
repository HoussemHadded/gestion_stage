<?php

// app/Enums/UserRole.php

namespace App\Enums;

enum UserRole: string
{
    case Admin      = 'admin';
    case Student    = 'student';
    case Entreprise = 'entreprise';

    /** Libellé affiché dans les vues */
    public function label(): string
    {
        return match ($this) {
            self::Admin      => 'Administrateur',
            self::Student    => 'Étudiant',
            self::Entreprise => 'Entreprise',
        };
    }

    /**
     * Retourne le nom de route nommée du tableau de bord pour ce rôle.
     */
    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin      => 'admin.dashboard',
            self::Student    => 'student.dashboard',
            self::Entreprise => 'entreprise.dashboard',
        };
    }

    /**
     * Retourne la classe CSS Bootstrap badge pour ce rôle.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin      => 'badge bg-danger',
            self::Student    => 'badge bg-primary',
            self::Entreprise => 'badge bg-warning text-dark',
        };
    }

    /**
     * Recherche inverse : retrouve un UserRole à partir de son libellé français.
     */
    public static function tryFromLabel(string $label): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->label() === $label) {
                return $case;
            }
        }
        return null;
    }
}
