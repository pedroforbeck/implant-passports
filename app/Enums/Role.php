<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Doctor = 'medico';
    case Patient = 'paciente';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Doctor => 'Médico',
            self::Patient => 'Paciente',
        };
    }
}
