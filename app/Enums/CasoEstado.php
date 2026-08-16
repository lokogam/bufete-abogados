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

    /**
     * Clase CSS del badge para mostrar el estado con su color.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::EnTramite => 'badge-amber',
            self::Archivado => 'badge-gray',
            self::Sentenciado => 'badge-green',
            self::Desistido => 'badge-red',
            self::Suspendido => 'badge-orange',
        };
    }

    /**
     * Resuelve un estado a partir de su etiqueta, sin distinguir
     * mayúsculas, espacios ni tildes. Devuelve null si no coincide.
     */
    public static function fromLabel(string $label): ?self
    {
        $normalize = static fn (string $value): string => strtr(
            mb_strtolower(trim($value)),
            ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n'],
        );

        $label = $normalize($label);

        foreach (self::cases() as $case) {
            if ($normalize($case->label()) === $label) {
                return $case;
            }
        }

        return null;
    }
}
