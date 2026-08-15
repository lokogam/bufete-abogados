<?php

declare(strict_types=1);

namespace App\Exports;

use App\Exports\Sheets\CasosDeAbogadoSheet;
use App\Models\Abogado;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Exporta clientes y sus casos, agrupados en una hoja independiente por abogado.
 */
class CasosPorAbogadoExport implements Export, WithMultipleSheets
{
    /**
     * Una hoja por cada abogado.
     *
     * @return array<int, CasosDeAbogadoSheet>
     */
    public function sheets(): array
    {
        return Abogado::query()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get()
            ->map(fn (Abogado $abogado) => new CasosDeAbogadoSheet($abogado))
            ->all();
    }
}
