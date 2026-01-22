<?php

// app/Services/OpenAI/OpenAIService.php
namespace App\Services\OpenAI;

use App\Exceptions\OpenAIException;
use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.key'));
    }

    protected function callChatCompletion(string $prompt, array $options = []): string
    {
        try {
            $response = $this->client->chat()->create([
                'model' => config('services.openai.model', 'gpt-4-turbo-preview'),
                'messages' => [
                    ['role' => 'system', 'content' => $options['system'] ?? 'Tu es un assistant pédagogique expert.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 2000,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            throw new OpenAIException(
                "Erreur lors de l'appel à OpenAI: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
