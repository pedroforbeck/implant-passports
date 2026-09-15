<?php

namespace App\Enums;

enum DeviceType: string
{
    case Pacemaker = 'marcapasso';
    case Icd = 'cdi';
    case Crt = 'ressincronizador';
    case LoopRecorder = 'monitor_implantavel';

    public function label(): string
    {
        return match ($this) {
            self::Pacemaker => 'Marca-passo',
            self::Icd => 'Cardiodesfibrilador implantável (CDI)',
            self::Crt => 'Ressincronizador (TRC)',
            self::LoopRecorder => 'Monitor de eventos implantável',
        };
    }
}
