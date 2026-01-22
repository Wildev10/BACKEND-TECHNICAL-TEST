<?php

// app/Http/Requests/Education/ConceptExplanationRequest.php
namespace App\Http\Requests\Education;

use App\Enums\SchoolLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConceptExplanationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'concept' => ['required', 'string', 'max:500'],
            'subject' => ['required', 'string', 'max:100'],
            'level' => ['required', Rule::enum(SchoolLevel::class)],
            'additional_context' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
