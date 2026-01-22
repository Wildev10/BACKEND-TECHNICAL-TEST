<?php

namespace Database\Factories;

use App\Enums\EducationRequestStatus;
use App\Enums\EducationRequestType;
use App\Enums\SchoolLevel;
use App\Models\EducationRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EducationRequest>
 */
class EducationRequestFactory extends Factory
{
    protected $model = EducationRequest::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(EducationRequestType::cases());

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'status' => $this->faker->randomElement(EducationRequestStatus::cases()),
            'school_level' => $this->faker->randomElement(SchoolLevel::cases()),
            'subject' => $this->faker->randomElement([
                'Mathématiques',
                'Français',
                'Histoire',
                'Géographie',
                'Physique',
                'Chimie',
                'SVT',
                'Anglais',
            ]),
            'input_data' => $this->getInputDataForType($type),
            'result' => null,
            'error_message' => null,
            'processed_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EducationRequestStatus::PENDING,
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EducationRequestStatus::PROCESSING,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EducationRequestStatus::COMPLETED,
            'result' => ['content' => 'Sample result content'],
            'processed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EducationRequestStatus::FAILED,
            'error_message' => 'An error occurred during processing.',
            'processed_at' => now(),
        ]);
    }

    protected function getInputDataForType(EducationRequestType $type): array
    {
        return match ($type) {
            EducationRequestType::CONCEPT_EXPLANATION => [
                'concept' => $this->faker->sentence(),
                'subject' => $this->faker->word(),
                'school_level' => $this->faker->randomElement(SchoolLevel::cases())->value,
                'additional_context' => $this->faker->optional()->sentence(),
            ],
            EducationRequestType::EXERCISE_GENERATION => [
                'topic' => $this->faker->sentence(),
                'subject' => $this->faker->word(),
                'school_level' => $this->faker->randomElement(SchoolLevel::cases())->value,
                'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
                'exercise_count' => $this->faker->numberBetween(1, 10),
                'include_solutions' => $this->faker->boolean(),
            ],
            EducationRequestType::EXERCISE_CORRECTION => [
                'exercise' => $this->faker->paragraph(),
                'student_answer' => $this->faker->paragraph(),
                'subject' => $this->faker->word(),
                'school_level' => $this->faker->randomElement(SchoolLevel::cases())->value,
                'provide_detailed_feedback' => $this->faker->boolean(),
            ],
            EducationRequestType::LESSON_SUMMARY => [
                'lesson_content' => $this->faker->paragraphs(3, true),
                'subject' => $this->faker->word(),
                'school_level' => $this->faker->randomElement(SchoolLevel::cases())->value,
                'summary_type' => $this->faker->randomElement(['brief', 'detailed', 'bullet_points']),
                'include_key_concepts' => $this->faker->boolean(),
            ],
            EducationRequestType::QUIZ_GENERATION => [
                'topic' => $this->faker->sentence(),
                'subject' => $this->faker->word(),
                'school_level' => $this->faker->randomElement(SchoolLevel::cases())->value,
                'question_count' => $this->faker->numberBetween(1, 20),
                'question_type' => $this->faker->randomElement(['multiple_choice', 'true_false', 'mixed']),
                'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
                'include_answers' => $this->faker->boolean(),
            ],
        };
    }
}
