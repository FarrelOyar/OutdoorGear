<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case PROSES = 'proses';
    case SELESAI = 'selesai';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public function label(): string
    {
        return match($this) {
            self::PROSES => 'proses',
            self::SELESAI => 'selesai',
        };
    }
}