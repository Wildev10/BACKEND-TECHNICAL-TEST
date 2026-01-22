<?php

namespace App\Policies;

use App\Models\EducationRequest;
use App\Models\User;

class EducationRequestPolicy
{
    public function view(User $user, EducationRequest $educationRequest): bool
    {
        return $user->id === $educationRequest->user_id;
    }

    public function update(User $user, EducationRequest $educationRequest): bool
    {
        return $user->id === $educationRequest->user_id;
    }

    public function delete(User $user, EducationRequest $educationRequest): bool
    {
        return $user->id === $educationRequest->user_id;
    }
}
