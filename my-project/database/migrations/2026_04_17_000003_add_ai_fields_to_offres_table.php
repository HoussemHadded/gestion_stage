<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: add_ai_fields_to_offres_table
 *
 * Adds two nullable columns to the existing `offres` table to support
 * AI matching. Existing rows are unaffected (columns are nullable).
 *
 *  - type           : internship type e.g. "stage PFE", "stage d'été", "alternance"
 *  - level_required : expected student level e.g. "Bac+2", "Bac+3", "Master"
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            // Internship type — nullable to keep backward compatibility
            $table->string('type')->nullable()->after('lieu');

            // Required academic/experience level
            $table->string('level_required')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn(['type', 'level_required']);
        });
    }
};
