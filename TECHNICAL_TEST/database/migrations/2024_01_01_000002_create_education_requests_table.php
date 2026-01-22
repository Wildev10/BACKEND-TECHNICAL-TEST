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
            $table->string('type'); // explication_concept, generation_exercices, etc.
            $table->string('status')->default('pending'); // en_attente, en_traitement, termine, echoue
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
