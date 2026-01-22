<?php
// app/Http/Controllers/Education/ExerciseCorrectionController.php
namespace App\Http\Controllers\Education;

use App\Enums\EducationRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Education\ExerciseCorrectionRequest;
use App\Jobs\OpenAI\ProcessExerciseCorrection;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class ExerciseCorrectionController extends Controller
{
    public function store(ExerciseCorrectionRequest $request): JsonResponse
    {
        $educationRequest = EducationRequest::create([
            'user_id' => auth()->id(),
            'type' => EducationRequestType::EXERCISE_CORRECTION,
            'prompt' => $request->exercise,
            'metadata' => [
                'student_answer' => $request->student_answer,
                'subject' => $request->subject,
            ],
        ]);

        ProcessExerciseCorrection::dispatch($educationRequest);

        return response()->json([
            'success' => true,
            'message' => 'Correction lancée',
            'data' => [
                'request_id' => $educationRequest->id,
                'status' => $educationRequest->status,
            ],
        ], 201);
    }
}
