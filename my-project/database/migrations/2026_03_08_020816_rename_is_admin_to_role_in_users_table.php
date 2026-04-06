<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration historique : renommage de la colonne `is_admin` (tinyint) → `role` (string/enum).
 *
 * NOTE: La migration originale `2026_02_28_225854_create_users_table.php` crée
 * désormais directement la colonne `role` avec les valeurs enum correctes.
 * Cette migration de renommage est donc un no-op pour les installations fraîches
 * (migrate:fresh). Elle est conservée pour tracer l'historique du changement
 * et pour les bases de données existantes qui auraient encore l'ancienne colonne.
 *
 * Si la colonne `is_admin` existe encore (installation en production issue
 * d'un état antérieur), ce code la renomme et la convertit au bon type.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Guard : ne rien faire si la colonne `role` existe déjà
        // (ce qui est le cas pour toute installation via migrate:fresh).
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // Renommer l'ancienne colonne is_admin → role
            $table->renameColumn('is_admin', 'role');
        });
    }

    public function down(): void
    {
        // Guard symétrique
        if (Schema::hasColumn('users', 'is_admin')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role', 'is_admin');
        });
    }
};
