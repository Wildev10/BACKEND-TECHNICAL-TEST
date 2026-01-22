<?php

// app/Services/OpenAI/QuizGeneratorService.php
namespace App\Services\OpenAI;

class QuizGeneratorService extends OpenAIService
{
    public function generateQuiz(string $topic, array $metadata): string
    {
        $level = $metadata['level'];
        $subject = $metadata['subject'];
        $questionCount = $metadata['question_count'];
        $questionType = $metadata['question_type'];

        $typeInstructions = match($questionType) {
            'mcq' => 'uniquement des QCM avec 4 choix et une seule bonne réponse',
            'open' => 'uniquement des questions ouvertes',
            default => 'un mélange de QCM et de questions ouvertes',
        };

        $systemPrompt = "Tu es un créateur de quiz pédagogiques en {$subject}.
        Génère des questions pertinentes, claires et adaptées au niveau {$level}.
        Les questions doivent tester la compréhension et non la simple mémorisation.";

        $userPrompt = "Crée un quiz de {$questionCount} question(s) ({$typeInstructions}) sur : {$topic}

        Format pour les QCM :
        **Question 1 :**
        [Question]
        a) [Choix A]
        b) [Choix B]
        c) [Choix C]
        d) [Choix D]
        **Réponse correcte :** [lettre]

        Format pour les questions ouvertes :
        **Question 1 :**
        [Question]
        **Éléments de réponse attendus :** [liste]";

        return $this->callChatCompletion($userPrompt, [
            'system' => $systemPrompt,
            'temperature' => 0.8,
            'max_tokens' => 3000,
        ]);
    }
}
