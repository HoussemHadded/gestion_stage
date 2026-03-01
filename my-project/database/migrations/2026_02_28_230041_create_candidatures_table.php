<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('offre_id')
                  ->constrained('offres')
                  ->onDelete('cascade');

            $table->unique(['student_id', 'offre_id']);

            $table->string('cv');
            $table->enum('statut', ['en_attente', 'accepte', 'refuse']);
            $table->date('date_candidature');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};