<?php

declare(strict_types=1);

namespace App\Exports\Sheets;

use App\Models\Abogado;
use App\Models\Caso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Hoja del libro con los casos asignados a un abogado concreto.
 */
class CasosDeAbogadoSheet implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private readonly Abogado $abogado) {}

    public function query(): Builder
    {
        return Caso::query()
            ->with(['cliente', 'abogados'])
            ->whereHas('abogados', fn (Builder $query) => $query->where('abogados.id', $this->abogado->id))
            ->orderBy('numero_expediente');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Nº Expediente',
            'Cliente',
            'Cédula',
            'Estado',
            'Fecha inicio',
            'Fecha finalización',
            'Descripción',
            'Abogados del caso',
        ];
    }

    /**
     * @return array<int, string|null>
     */
    public function map($caso): array
    {
        /** @var Caso $caso */
        return [
            $caso->numero_expediente,
            $caso->cliente?->nombre_completo,
            $caso->cliente?->cedula,
            $caso->estado->label(),
            $caso->fecha_inicio?->toDateString(),
            $caso->fecha_finalizacion?->toDateString(),
            $caso->descripcion,
            $caso->abogados->map(fn (Abogado $abogado) => $abogado->nombre_completo)->implode(', '),
        ];
    }

    public function title(): string
    {
        return Str::limit("{$this->abogado->nombre} {$this->abogado->apellido}", 31, '');
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
