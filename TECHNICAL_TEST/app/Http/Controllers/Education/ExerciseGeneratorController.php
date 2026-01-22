<?php


// app/Http/Controllers/Education/ExerciseGeneratorController.php
namespace App\Http\Controllers\Education;

use App\Enums\EducationRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Education\ExerciseGenerationRequest;
use App\Jobs\OpenAI\ProcessExerciseGeneration;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class ExerciseGeneratorController extends Controller
{
    public function store(ExerciseGenerationRequest $request): JsonResponse
    {
        $educationRequest = EducationRequest::create([
            'user_id' => auth()->id(),
            'type' => EducationRequestType::EXERCISE_GENERATION,
            'prompt' => $request->topic,
            'metadata' => [
                'subject' => $request->subject,
                'level' => $request->level,
                'quantity' => $request->quantity,
                'difficulty' => $request->difficulty,
            ],
        ]);

        ProcessExerciseGeneration::dispatch($educationRequest);

        return response()->json([
            'success' => true,
            'message' => 'Génération d\'exercices lancée',
            'data' => [
                'request_id' => $educationRequest->id,
                'status' => $educationRequest->status,
            ],
        ], 201);
    }
}
