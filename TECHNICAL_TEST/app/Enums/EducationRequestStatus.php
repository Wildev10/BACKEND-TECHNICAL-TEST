<?php

// app/Enums/EducationRequestStatus.php
namespace App\Enums;

enum EducationRequestStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::PROCESSING => 'En traitement',
            self::COMPLETED => 'Terminé',
            self::FAILED => 'Échoué',
        };
    }
}
