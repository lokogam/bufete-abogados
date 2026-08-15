<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\CasosPorAbogadoExport;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * Descarga el Excel de clientes y casos (una hoja por abogado).
     */
    public function download(CasosPorAbogadoExport $export, ExcelWriter $excel): BinaryFileResponse
    {
        return $excel->download($export, 'casos_por_abogado.xlsx');
    }
}
