<?php

namespace Tests\Unit\Services;

use App\Exceptions\OpenAIException;
use App\Services\OpenAI\OpenAIService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenAIServiceTest extends TestCase
{
    private OpenAIService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OpenAIService();
    }

    public function test_chat_returns_response_on_success(): void
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'id' => 'chatcmpl-123',
                'object' => 'chat.completion',
                'created' => 1677652288,
                'model' => 'gpt-4',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Le théorème de Pythagore est...',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 50,
                    'completion_tokens' => 100,
                    'total_tokens' => 150,
                ],
            ], 200),
        ]);

        $messages = [
            $this->service->systemMessage('Tu es un enseignant expert.'),
            $this->service->userMessage('Explique le théorème de Pythagore.'),
        ];

        $result = $this->service->chat($messages);

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('usage', $result);
        $this->assertArrayHasKey('model', $result);
        $this->assertEquals('Le théorème de Pythagore est...', $result['content']);
    }

    public function test_chat_throws_exception_on_api_error(): void
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid API key',
                    'type' => 'invalid_request_error',
                ],
            ], 401),
        ]);

        $this->expectException(OpenAIException::class);

        $messages = [
            $this->service->userMessage('Test message'),
        ];

        $this->service->chat($messages);
    }

    public function test_system_message_creates_correct_format(): void
    {
        $message = $this->service->systemMessage('You are an expert teacher.');

        $this->assertEquals([
            'role' => 'system',
            'content' => 'You are an expert teacher.',
        ], $message);
    }

    public function test_user_message_creates_correct_format(): void
    {
        $message = $this->service->userMessage('Explain this concept.');

        $this->assertEquals([
            'role' => 'user',
            'content' => 'Explain this concept.',
        ], $message);
    }

    public function test_assistant_message_creates_correct_format(): void
    {
        $message = $this->service->assistantMessage('Here is the explanation.');

        $this->assertEquals([
            'role' => 'assistant',
            'content' => 'Here is the explanation.',
        ], $message);
    }
}
