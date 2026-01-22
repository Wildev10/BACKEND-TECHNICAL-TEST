<?php


// app/Http/Requests/Education/QuizGenerationRequest.php
namespace App\Http\Requests\Education;

use App\Enums\SchoolLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizGenerationRequest extends FormRequest
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
            'question_count' => ['required', 'integer', 'min:1', 'max:20'],
            'question_type' => ['required', 'string', 'in:mcq,open,mixed'],
        ];
    }
}
