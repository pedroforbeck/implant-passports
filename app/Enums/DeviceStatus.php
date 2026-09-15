<?php

namespace App\Enums;

enum DeviceStatus: string
{
    case Active = 'ativo';
    case Explanted = 'explantado';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Ativo',
            self::Explanted => 'Explantado',
        };
    }
}
