<?php
// app/Http/Requests/Education/LessonSummaryRequest.php
namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;

class LessonSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson_content' => ['required', 'string', 'max:10000'],
            'subject' => ['required', 'string', 'max:100'],
            'summary_length' => ['nullable', 'string', 'in:short,medium,long'],
        ];
    }
}
