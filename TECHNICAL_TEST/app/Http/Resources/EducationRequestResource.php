<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ],
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'color' => $this->status->color(),
            ],
            'school_level' => [
                'value' => $this->school_level->value,
                'label' => $this->school_level->label(),
                'category' => $this->school_level->category(),
            ],
            'subject' => $this->subject,
            'input_data' => $this->input_data,
            'result' => $this->when($this->status->value === 'completed', $this->result),
            'error_message' => $this->when($this->status->value === 'failed', $this->error_message),
            'processed_at' => $this->processed_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
