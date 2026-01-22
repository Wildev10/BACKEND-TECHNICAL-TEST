<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // concept_explanation, exercise_generation, etc.
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('prompt'); // Contenu envoyé
            $table->longText('response')->nullable(); // Réponse générée
            $table->json('metadata')->nullable(); // Données additionnelles (niveau, sujet, etc.)
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_requests');
    }
};
