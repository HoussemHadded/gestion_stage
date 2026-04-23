<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne "role" en string pour éviter les erreurs d'enum restrictif, puis ajouter valeur par défaut.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('etudiant')->change();
        });

        // Effectuer la migration des données ("student" -> "etudiant")
        DB::table('users')->where('role', 'student')->update(['role' => 'etudiant']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir en arrière pour les données
        DB::table('users')->where('role', 'etudiant')->update(['role' => 'student']);
        
        // Revenir en arrière pour la définition de la colonne (Peut causer une erreur sous SQLite)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'student', 'entreprise'])->change();
        });
    }
};
