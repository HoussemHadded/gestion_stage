<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_matches_table
 *
 * Stores the AI matching results between a student and an internship offer.
 *
 *  - score   : overall compatibility score, 0.00 – 100.00
 *  - details : JSON breakdown of each scoring criterion
 *              e.g. {"skills": 40, "level": 15, "location": 8, ...}
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            // FK → users (role=student)
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade');

            // FK → offres
            $table->foreignId('offre_id')
                ->constrained('offres')
                ->onDelete('cascade');

            // Overall compatibility score (0 – 100, two decimal places)
            $table->decimal('score', 5, 2)->default(0);

            // JSON payload: per-criterion breakdown + AI explanation
            $table->json('details')->nullable();

            $table->timestamps();

            // One match record per (student, offer) pair — recalculation updates it
            $table->unique(['student_id', 'offre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
