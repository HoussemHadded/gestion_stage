<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: add_cv_text_to_users_table
 *
 * Adds a nullable `cv_text` column to `users`.
 * Students paste or upload their CV text here; the CV parsing service
 * scans this field to extract and save skills into student_skills.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Raw CV text used for NLP skill extraction
            $table->text('cv_text')->nullable()->after('company_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cv_text');
        });
    }
};
