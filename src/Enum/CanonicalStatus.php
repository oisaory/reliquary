<?php

namespace App\Enum;

enum CanonicalStatus: string
{
    case CANONIZATION = 'Canonizzazione';
    case BEATIFICATION = 'Beatificazione';
    case VENERATION = 'Venerazione';
    case SERVANT_OF_GOD = 'Servo di Dio';

    public function getLabel(): string
    {
        return match($this) {
            self::CANONIZATION => 'Canonization',
            self::BEATIFICATION => 'Beatification',
            self::VENERATION => 'Veneration',
            self::SERVANT_OF_GOD => 'Servant of God',
        };
    }

    public function getTitle(): string
    {
        return match($this) {
            self::CANONIZATION => 'Saint',
            self::BEATIFICATION => 'Blessed',
            self::VENERATION => 'Venerable',
            self::SERVANT_OF_GOD => 'Servant of God',
        };
    }

    public function getTitleTransKey(): string
    {
        return match($this) {
            self::CANONIZATION => 'saint.canonical_status_titles.canonization',
            self::BEATIFICATION => 'saint.canonical_status_titles.beatification',
            self::VENERATION => 'saint.canonical_status_titles.veneration',
            self::SERVANT_OF_GOD => 'saint.canonical_status_titles.servant_of_god',
        };
    }

    public static function fromString(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return match($value) {
            'Canonizzazione' => self::CANONIZATION,
            'Beatificazione' => self::BEATIFICATION,
            'Venerazione' => self::VENERATION,
            'Servo di Dio' => self::SERVANT_OF_GOD,
            default => throw new \ValueError("Unknown canonical status: $value"),
        };
    }
}
