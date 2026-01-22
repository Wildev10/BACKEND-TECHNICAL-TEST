<?php

// app/Services/OpenAI/LessonSummaryService.php
namespace App\Services\OpenAI;

class LessonSummaryService extends OpenAIService
{
    public function summarize(string $lessonContent, array $metadata): string
    {
        $subject = $metadata['subject'];
        $length = $metadata['summary_length'];

        $lengthInstructions = match($length) {
            'short' => 'Résumé court (150-200 mots)',
            'long' => 'Résumé détaillé (500-700 mots)',
            default => 'Résumé moyen (300-400 mots)',
        };

        $systemPrompt = "Tu es un expert en synthèse pédagogique en {$subject}.
        Extrais les points clés et les concepts essentiels.
        Structure le résumé de manière logique et hiérarchisée.";

        $userPrompt = "Fais un résumé de cette leçon ({$lengthInstructions}) :

        {$lessonContent}

        Le résumé doit inclure :
        - Les concepts principaux
        - Les points clés à retenir
        - Une structuration claire";

        return $this->callChatCompletion($userPrompt, [
            'system' => $systemPrompt,
            'temperature' => 0.5,
            'max_tokens' => 3000,
        ]);
    }
}
