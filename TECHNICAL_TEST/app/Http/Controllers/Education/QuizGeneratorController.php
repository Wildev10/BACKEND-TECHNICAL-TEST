<?php


// app/Http/Controllers/Education/QuizGeneratorController.php
namespace App\Http\Controllers\Education;

use App\Enums\EducationRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Education\QuizGenerationRequest;
use App\Jobs\OpenAI\ProcessQuizGeneration;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class QuizGeneratorController extends Controller
{
    public function store(QuizGenerationRequest $request): JsonResponse
    {
        $educationRequest = EducationRequest::create([
            'user_id' => auth()->id(),
            'type' => EducationRequestType::QUIZ_GENERATION,
            'prompt' => $request->topic,
            'metadata' => [
                'subject' => $request->subject,
                'level' => $request->level,
                'question_count' => $request->question_count,
                'question_type' => $request->question_type,
            ],
        ]);

        ProcessQuizGeneration::dispatch($educationRequest);

        return response()->json([
            'success' => true,
            'message' => 'Quiz en cours de génération',
            'data' => [
                'request_id' => $educationRequest->id,
                'status' => $educationRequest->status,
            ],
        ], 201);
    }
}
