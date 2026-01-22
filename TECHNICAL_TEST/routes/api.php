<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Education\ConceptExplanationController;
use App\Http\Controllers\Education\ExerciseGeneratorController;
use App\Http\Controllers\Education\ExerciseCorrectionController;
use App\Http\Controllers\Education\LessonSummaryController;
use App\Http\Controllers\Education\QuizGeneratorController;
use App\Http\Controllers\Education\HistoryController;

// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {

    // Authentification
    Route::post('auth/logout', [LoginController::class, 'logout']);
    Route::get('auth/me', [LoginController::class, 'me']);

    // Endpoints éducatifs
    Route::prefix('education')->group(function () {

        // Explication de concept
        Route::post('explain-concept', [ConceptExplanationController::class, 'store']);

        // Génération d'exercices
        Route::post('generate-exercises', [ExerciseGeneratorController::class, 'store']);

        // Correction d'exercice
        Route::post('correct-exercise', [ExerciseCorrectionController::class, 'store']);

        // Résumé de leçon
        Route::post('summarize-lesson', [LessonSummaryController::class, 'store']);

        // Génération de quiz
        Route::post('generate-quiz', [QuizGeneratorController::class, 'store']);

        // Historique des requêtes
        Route::get('requests', [HistoryController::class, 'index']);
        Route::get('requests/{educationRequest}', [HistoryController::class, 'show']);
    });
});
