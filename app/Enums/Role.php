<?php

namespace App\Enums;

/**
 * Perfis de acesso guardados na coluna users.role.
 */
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
