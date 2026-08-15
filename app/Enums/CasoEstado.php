<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Estados posibles de un caso judicial.
 */
enum CasoEstado: string
{
    case EnTramite = 'en_tramite';
    case Archivado = 'archivado';
    case Sentenciado = 'sentenciado';
    case Desistido = 'desistido';
    case Suspendido = 'suspendido';

    /**
     * Etiqueta legible para mostrar en la interfaz.
     */
    public function label(): string
    {
        return match ($this) {
            self::EnTramite => 'En trámite',
            self::Archivado => 'Archivado',
            self::Sentenciado => 'Sentenciado',
            self::Desistido => 'Desistido',
            self::Suspendido => 'Suspendido',
        };
    }

    /**
     * @return array<string, string> pares valor => etiqueta
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $estado) {
            $labels[$estado->value] = $estado->label();
        }

        return $labels;
    }
}
