<?php

// app/Http/Controllers/Education/LessonSummaryController.php
namespace App\Http\Controllers\Education;

use App\Enums\EducationRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Education\LessonSummaryRequest;
use App\Jobs\OpenAI\ProcessLessonSummary;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class LessonSummaryController extends Controller
{
    public function store(LessonSummaryRequest $request): JsonResponse
    {
        $educationRequest = EducationRequest::create([
            'user_id' => auth()->id(),
            'type' => EducationRequestType::LESSON_SUMMARY,
            'prompt' => $request->lesson_content,
            'metadata' => [
                'subject' => $request->subject,
                'summary_length' => $request->summary_length ?? 'medium',
            ],
        ]);

        ProcessLessonSummary::dispatch($educationRequest);

        return response()->json([
            'success' => true,
            'message' => 'Résumé en cours de génération',
            'data' => [
                'request_id' => $educationRequest->id,
                'status' => $educationRequest->status,
            ],
        ], 201);
    }
}
