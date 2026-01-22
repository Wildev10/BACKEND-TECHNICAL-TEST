<?php

// app/Services/OpenAI/ConceptExplanationService.php

namespace App\Services\OpenAI;

class ConceptExplanationService extends OpenAIService
{
    public function explain(string $concept, array $metadata): string
    {
        $level = $metadata['level'] ?? 'high_school';
        $subject = $metadata['subject'];
        $context = $metadata['additional_context'] ?? '';

        $systemPrompt = "Tu es un professeur expert en {$subject}.
        Explique les concepts de manière claire et adaptée au niveau {$level}.
        Utilise des exemples concrets et des analogies si nécessaire.";

        $userPrompt = "Explique le concept suivant : {$concept}";

        if ($context) {
            $userPrompt .= "\n\nContexte additionnel : {$context}";
        }

        return $this->callChatCompletion($userPrompt, [
            'system' => $systemPrompt,
            'temperature' => 0.7,
        ]);
    }
}
