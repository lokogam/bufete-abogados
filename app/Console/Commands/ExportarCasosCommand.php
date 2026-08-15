<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Exports\CasosPorAbogadoExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Genera un libro Excel con los clientes y sus casos,
 * una hoja independiente por cada abogado.
 */
class ExportarCasosCommand extends Command
{
    protected $signature = 'casos:export
                            {--nombre=casos_por_abogado.xlsx : Nombre del archivo generado}';

    protected $description = 'Genera un Excel con clientes y casos, una hoja por abogado';

    public function handle(ExcelWriter $excel): int
    {
        $nombre = $this->option('nombre');
        $disco = 'local';

        $excel->store(new CasosPorAbogadoExport, $nombre, $disco);

        $ruta = storage_path("app/private/{$nombre}");

        $this->info("Libro Excel generado correctamente: {$ruta}");

        return self::SUCCESS;
    }
}
