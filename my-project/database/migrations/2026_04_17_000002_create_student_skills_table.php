<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_student_skills_table
 *
 * Pivot table linking students (users with role=student) to their skills.
 * Stores the proficiency level alongside the relationship.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();

            // FK → users table (student)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // FK → skills table
            $table->foreignId('skill_id')
                ->constrained('skills')
                ->onDelete('cascade');

            // Proficiency level: beginner | intermediate | advanced | expert
            $table->string('level')->default('beginner');

            $table->timestamps();

            // A student cannot have the same skill twice
            $table->unique(['user_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_skills');
    }
};
