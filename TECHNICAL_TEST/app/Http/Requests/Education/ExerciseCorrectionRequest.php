<?php

// app/Http/Requests/Education/ExerciseCorrectionRequest.php
namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exercise' => ['required', 'string', 'max:2000'],
            'student_answer' => ['required', 'string', 'max:2000'],
            'subject' => ['required', 'string', 'max:100'],
        ];
    }
}
