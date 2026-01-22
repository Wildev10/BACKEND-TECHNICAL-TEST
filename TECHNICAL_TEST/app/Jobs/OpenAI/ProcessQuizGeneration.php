<?php

// app/Jobs/OpenAI/ProcessQuizGeneration.php
namespace App\Jobs\OpenAI;

use App\Models\EducationRequest;
use App\Services\OpenAI\QuizGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessQuizGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public EducationRequest $educationRequest
    ) {}

    public function handle(QuizGeneratorService $service): void
    {
        $this->educationRequest->markAsProcessing();

        try {
            $response = $service->generateQuiz(
                $this->educationRequest->prompt,
                $this->educationRequest->metadata
            );

            $this->educationRequest->markAsCompleted($response);

            Log::info('Quiz generation completed', [
                'request_id' => $this->educationRequest->id,
            ]);
        } catch (\Exception $e) {
            $this->educationRequest->markAsFailed($e->getMessage());

            Log::error('Quiz generation failed', [
                'request_id' => $this->educationRequest->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->educationRequest->markAsFailed(
            'Échec après plusieurs tentatives: ' . $exception->getMessage()
        );
    }
}
