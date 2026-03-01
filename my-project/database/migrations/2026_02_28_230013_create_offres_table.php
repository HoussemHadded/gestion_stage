<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id(); // id bigint PK
            $table->string('titre');
            $table->text('description');
            $table->date('date_publication');
            $table->foreignId('entreprise_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};