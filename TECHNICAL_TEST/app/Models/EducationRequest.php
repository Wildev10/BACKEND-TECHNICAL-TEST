<?php

namespace App\Models;

use App\Enums\EducationRequestStatus;
use App\Enums\EducationRequestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'prompt',
        'response',
        'metadata',
        'error_message',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => EducationRequestType::class,
            'status' => EducationRequestStatus::class,
            'metadata' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsPending(): void
    {
        $this->update(['status' => EducationRequestStatus::PENDING]);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => EducationRequestStatus::PROCESSING]);
    }

    public function markAsCompleted(string $response): void
    {
        $this->update([
            'status' => EducationRequestStatus::COMPLETED,
            'response' => $response,
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => EducationRequestStatus::FAILED,
            'error_message' => $error,
            'processed_at' => now(),
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->status === EducationRequestStatus::COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === EducationRequestStatus::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === EducationRequestStatus::PROCESSING;
    }

    public function hasFailed(): bool
    {
        return $this->status === EducationRequestStatus::FAILED;
    }
}
