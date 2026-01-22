<?php

// app/Http/Requests/Education/ExerciseGenerationRequest.php
namespace App\Http\Requests\Education;

use App\Enums\SchoolLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExerciseGenerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic' => ['required', 'string', 'max:500'],
            'subject' => ['required', 'string', 'max:100'],
            'level' => ['required', Rule::enum(SchoolLevel::class)],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'difficulty' => ['nullable', 'string', 'in:easy,medium,hard'],
        ];
    }
}
