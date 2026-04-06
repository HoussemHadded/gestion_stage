<?php

// database/migrations/2026_04_06_000001_drop_evaluations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Supprime définitivement la table evaluations.
 * Le rôle encadrant (supervisor) a été supprimé du projet.
 * Cette table n'est plus utilisée.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('evaluations');
    }

    public function down(): void
    {
        // Impossible de recréer sans le rôle encadrant.
        // Pour restaurer, utilisez git revert et relancez depuis la migration d'origine.
    }
};
