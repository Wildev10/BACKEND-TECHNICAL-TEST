<?php
// app/Services/OpenAI/ExerciseGeneratorService.php
namespace App\Services\OpenAI;

class ExerciseGeneratorService extends OpenAIService
{
    public function generate(string $topic, array $metadata): string
    {
        $level = $metadata['level'];
        $subject = $metadata['subject'];
        $quantity = $metadata['quantity'];
        $difficulty = $metadata['difficulty'] ?? 'medium';

        $systemPrompt = "Tu es un créateur d'exercices pédagogiques en {$subject}.
        Génère des exercices clairs, progressifs et adaptés au niveau {$level}.
        Chaque exercice doit avoir un énoncé précis.";

        $userPrompt = "Génère {$quantity} exercice(s) de niveau {$difficulty} sur : {$topic}

        Format attendu :
        **Exercice 1**
        [Énoncé]

        **Exercice 2**
        [Énoncé]
        ...";

        return $this->callChatCompletion($userPrompt, [
            'system' => $systemPrompt,
            'temperature' => 0.8,
        ]);
    }
}
