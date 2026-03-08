<?php

// Phase 4 — Gestion de Stage

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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('encadrant_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('offre_id')
                  ->constrained('offres')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('note')
                  ->comment('Note sur 20 (0–20)');

            $table->text('commentaire');

            $table->date('date_evaluation');

            $table->timestamps();

            // A supervisor can evaluate a student for a given offer only once
            $table->unique(['student_id', 'encadrant_id', 'offre_id'], 'evaluations_student_encadrant_offre_unique');

            // Performance indexes
            $table->index('student_id',   'evaluations_student_id_index');
            $table->index('encadrant_id', 'evaluations_encadrant_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
