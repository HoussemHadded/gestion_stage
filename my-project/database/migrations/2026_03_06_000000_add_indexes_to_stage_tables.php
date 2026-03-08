<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });

        Schema::table('offres', function (Blueprint $table) {
            $table->index('entreprise_id');
        });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('offre_id');
            $table->index('statut');
            $table->index('date_candidature');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });

        Schema::table('offres', function (Blueprint $table) {
            $table->dropIndex(['entreprise_id']);
        });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['offre_id']);
            $table->dropIndex(['statut']);
            $table->dropIndex(['date_candidature']);
        });
    }
};

