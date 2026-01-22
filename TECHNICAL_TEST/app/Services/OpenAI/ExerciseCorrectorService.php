<?php

// app/Services/OpenAI/ExerciseCorrectorService.php
namespace App\Services\OpenAI;

class ExerciseCorrectorService extends OpenAIService
{
    public function correct(string $exercise, array $metadata): string
    {
        $studentAnswer = $metadata['student_answer'];
        $subject = $metadata['subject'];

        $systemPrompt = "Tu es un correcteur bienveillant et pédagogique en {$subject}.
        Évalue la réponse de l'élève de manière constructive.
        Identifie les points forts et les axes d'amélioration.
        Donne une correction détaillée avec explications.";

        $userPrompt = "**Exercice :**\n{$exercise}\n\n**Réponse de l'élève :**\n{$studentAnswer}\n\n
        Fournis une correction complète avec :
        1. Évaluation générale
        2. Points forts
        3. Erreurs identifiées
        4. Correction détaillée
        5. Conseils d'amélioration";

        return $this->callChatCompletion($userPrompt, [
            'system' => $systemPrompt,
            'temperature' => 0.6,
            'max_tokens' => 2500,
        ]);
    }
}
