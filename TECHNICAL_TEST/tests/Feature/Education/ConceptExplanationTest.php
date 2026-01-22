<?php

namespace Tests\Feature\Education;

use App\Enums\EducationRequestStatus;
use App\Enums\EducationRequestType;
use App\Models\EducationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ConceptExplanationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_request_concept_explanation(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/education/concept-explanation', [
                'concept' => 'Le théorème de Pythagore',
                'subject' => 'Mathématiques',
                'school_level' => '3eme',
                'additional_context' => 'Pour préparer un contrôle',
            ]);

        $response->assertStatus(202)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'type',
                    'status',
                    'school_level',
                    'subject',
                    'input_data',
                    'created_at',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('education_requests', [
            'user_id' => $this->user->id,
            'type' => EducationRequestType::CONCEPT_EXPLANATION->value,
            'status' => EducationRequestStatus::PENDING->value,
            'subject' => 'Mathématiques',
        ]);
    }

    public function test_unauthenticated_user_cannot_request_concept_explanation(): void
    {
        $response = $this->postJson('/api/education/concept-explanation', [
            'concept' => 'Le théorème de Pythagore',
            'subject' => 'Mathématiques',
            'school_level' => '3eme',
        ]);

        $response->assertStatus(401);
    }

    public function test_concept_explanation_requires_concept(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/education/concept-explanation', [
                'subject' => 'Mathématiques',
                'school_level' => '3eme',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['concept']);
    }

    public function test_concept_explanation_requires_subject(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/education/concept-explanation', [
                'concept' => 'Le théorème de Pythagore',
                'school_level' => '3eme',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject']);
    }

    public function test_concept_explanation_requires_valid_school_level(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/education/concept-explanation', [
                'concept' => 'Le théorème de Pythagore',
                'subject' => 'Mathématiques',
                'school_level' => 'invalid_level',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['school_level']);
    }

    public function test_user_can_view_request_history(): void
    {
        EducationRequest::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type' => EducationRequestType::CONCEPT_EXPLANATION,
            'status' => EducationRequestStatus::COMPLETED,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/education/history');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ])
            ->assertJson([
                'success' => true,
                'meta' => [
                    'total' => 3,
                ],
            ]);
    }

    public function test_user_can_view_specific_request(): void
    {
        $educationRequest = EducationRequest::factory()->create([
            'user_id' => $this->user->id,
            'type' => EducationRequestType::CONCEPT_EXPLANATION,
            'status' => EducationRequestStatus::COMPLETED,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/education/history/{$educationRequest->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'type',
                    'status',
                ],
            ]);
    }

    public function test_user_cannot_view_another_users_request(): void
    {
        $otherUser = User::factory()->create();
        $educationRequest = EducationRequest::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/education/history/{$educationRequest->id}");

        $response->assertStatus(404);
    }
}
