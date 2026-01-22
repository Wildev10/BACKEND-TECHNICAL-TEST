<?php

namespace App\Jobs\OpenAI;

use App\Enums\SchoolLevel;
use App\Models\EducationRequest;
use App\Services\OpenAI\ExerciseGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessExerciseGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 180;

    public function __construct(
        protected EducationRequest $educationRequest
    ) {}

    public function handle(ExerciseGeneratorService $service): void
    {
        try {
            $this->educationRequest->markAsProcessing();

            $inputData = $this->educationRequest->input_data;

            $result = $service->generate(
                topic: $inputData['topic'],
                subject: $inputData['subject'],
                schoolLevel: SchoolLevel::from($inputData['school_level']),
                difficulty: $inputData['difficulty'],
                exerciseCount: $inputData['exercise_count'],
                includeSolutions: $inputData['include_solutions'] ?? true
            );

            $this->educationRequest->markAsCompleted($result);

            Log::info('Exercise generation processed successfully', [
                'request_id' => $this->educationRequest->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Exercise generation processing failed', [
                'request_id' => $this->educationRequest->id,
                'error' => $e->getMessage(),
            ]);

            $this->educationRequest->markAsFailed($e->getMessage());

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Exercise generation job failed permanently', [
            'request_id' => $this->educationRequest->id,
            'error' => $exception->getMessage(),
        ]);

        $this->educationRequest->markAsFailed($exception->getMessage());
    }
}
