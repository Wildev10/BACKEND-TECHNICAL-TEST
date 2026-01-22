<?php

// app/Http/Controllers/Education/ConceptExplanationController.php
namespace App\Http\Controllers\Education;

use App\Enums\EducationRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Education\ConceptExplanationRequest;
use App\Jobs\OpenAI\ProcessConceptExplanation;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class ConceptExplanationController extends Controller
{
    public function store(ConceptExplanationRequest $request): JsonResponse
    {
        $educationRequest = EducationRequest::create([
            'user_id' => auth()->id(),
            'type' => EducationRequestType::CONCEPT_EXPLANATION,
            'prompt' => $request->concept,
            'metadata' => [
                'subject' => $request->subject,
                'level' => $request->level,
                'additional_context' => $request->additional_context,
            ],
        ]);

        ProcessConceptExplanation::dispatch($educationRequest);

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'explication créée avec succès',
            'data' => [
                'request_id' => $educationRequest->id,
                'status' => $educationRequest->status,
            ],
        ], 201);
    }
}
