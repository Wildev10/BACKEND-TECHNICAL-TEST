<?php

// app/Enums/EducationRequestType.php
namespace App\Enums;

enum EducationRequestType: string
{
    case CONCEPT_EXPLANATION = 'concept_explanation';
    case EXERCISE_GENERATION = 'exercise_generation';
    case EXERCISE_CORRECTION = 'exercise_correction';
    case LESSON_SUMMARY = 'lesson_summary';
    case QUIZ_GENERATION = 'quiz_generation';

    public function label(): string
    {
        return match($this) {
            self::CONCEPT_EXPLANATION => 'Explication de concept',
            self::EXERCISE_GENERATION => 'Génération d\'exercices',
            self::EXERCISE_CORRECTION => 'Correction d\'exercice',
            self::LESSON_SUMMARY => 'Résumé de leçon',
            self::QUIZ_GENERATION => 'Génération de quiz',
        };
    }
}
