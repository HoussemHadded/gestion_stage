<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safe additive alteration of the ENUM
        DB::statement("ALTER TABLE candidatures MODIFY statut ENUM('en_attente', 'shortlisted', 'interview', 'accepte', 'refuse') NOT NULL DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safety protocol: push orphaned candidates back to 'en_attente' so rollback does not fail
        DB::table('candidatures')
            ->whereIn('statut', ['shortlisted', 'interview'])
            ->update(['statut' => 'en_attente']);

        // Reverse to old schema
        DB::statement("ALTER TABLE candidatures MODIFY statut ENUM('en_attente', 'accepte', 'refuse') NOT NULL DEFAULT 'en_attente'");
    }
};
