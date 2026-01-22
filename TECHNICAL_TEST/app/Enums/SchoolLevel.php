<?php

// app/Enums/SchoolLevel.php
namespace App\Enums;

enum SchoolLevel: string
{
    case PRIMARY = 'primary';
    case MIDDLE_SCHOOL = 'middle_school';
    case HIGH_SCHOOL = 'high_school';
    case UNIVERSITY = 'university';

    public function label(): string
    {
        return match($this) {
            self::PRIMARY => 'Primaire',
            self::MIDDLE_SCHOOL => 'Collège',
            self::HIGH_SCHOOL => 'Lycée',
            self::UNIVERSITY => 'Université',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::PRIMARY => 'Niveau élémentaire (6-11 ans)',
            self::MIDDLE_SCHOOL => 'Niveau collège (11-15 ans)',
            self::HIGH_SCHOOL => 'Niveau lycée (15-18 ans)',
            self::UNIVERSITY => 'Niveau universitaire (18+ ans)',
        };
    }
}
