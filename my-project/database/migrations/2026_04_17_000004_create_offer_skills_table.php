<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_offer_skills_table
 *
 * Pivot table linking offres to required skills.
 * Used by the matching engine to compare with student_skills.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_skills', function (Blueprint $table) {
            $table->id();

            // FK → offres table
            $table->foreignId('offre_id')
                ->constrained('offres')
                ->onDelete('cascade');

            // FK → skills table
            $table->foreignId('skill_id')
                ->constrained('skills')
                ->onDelete('cascade');

            $table->timestamps();

            // An offer cannot require the same skill twice
            $table->unique(['offre_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_skills');
    }
};
