<?php

// app/Http/Controllers/Education/HistoryController.php
namespace App\Http\Controllers\Education;

use App\Http\Controllers\Controller;
use App\Models\EducationRequest;
use Illuminate\Http\JsonResponse;

class HistoryController extends Controller
{
    public function index(): JsonResponse
    {
        $requests = auth()->user()
            ->educationRequests()
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'type' => $request->type->value,
                    'status' => $request->status->value,
                    'prompt' => $request->prompt,
                    'response' => $request->response,
                    'created_at' => $request->created_at,
                    'processed_at' => $request->processed_at,
                ];
            }),
        ]);
    }

    public function show(EducationRequest $educationRequest): JsonResponse
    {
        $this->authorize('view', $educationRequest);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $educationRequest->id,
                'type' => $educationRequest->type->value,
                'status' => $educationRequest->status->value,
                'prompt' => $educationRequest->prompt,
                'response' => $educationRequest->response,
                'metadata' => $educationRequest->metadata,
                'error_message' => $educationRequest->error_message,
                'created_at' => $educationRequest->created_at,
                'processed_at' => $educationRequest->processed_at,
            ],
        ]);
    }

    public function status(EducationRequest $educationRequest): JsonResponse
    {
        $this->authorize('view', $educationRequest);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $educationRequest->id,
                'status' => $educationRequest->status,
                'type' => $educationRequest->type,
                'completed' => $educationRequest->isCompleted(),
                'response' => $educationRequest->isCompleted()
                    ? $educationRequest->response
                    : null,
            ],
        ]);
    }
}
