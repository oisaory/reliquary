<?php

namespace App\Enum;

enum RelicDegree: string
{
    case UNKNOWN = 'unknown';
    case FIRST_CLASS = 'first-class';
    case SECOND_CLASS = 'second-class';
    case THIRD_CLASS = 'third-class';

    public function getLabel(): string
    {
        return match($this) {
            self::UNKNOWN => 'Unknown',
            self::FIRST_CLASS => 'First-Class',
            self::SECOND_CLASS => 'Second-Class',
            self::THIRD_CLASS => 'Third-Class',
        };
    }

    public function getTitleTransKey(): string
    {
        return match($this) {
            self::UNKNOWN => 'relic.degree.unknown',
            self::FIRST_CLASS => 'relic.degree.first_class',
            self::SECOND_CLASS => 'relic.degree.second_class',
            self::THIRD_CLASS => 'relic.degree.third_class',
        };
    }
}
