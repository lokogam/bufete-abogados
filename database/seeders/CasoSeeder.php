<?php

namespace Database\Seeders;

use App\Enums\CasoEstado;
use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use Illuminate\Database\Seeder;

class CasoSeeder extends Seeder
{
    /**
     * Datos de casos y sus relaciones con abogados de demostración.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function datos(): array
    {
        return [
            ['numero_expediente' => 'EXP-2024-0001', 'cliente' => '1012345678', 'fecha_inicio' => '2024-01-15', 'fecha_finalizacion' => null, 'estado' => CasoEstado::EnTramite, 'descripcion' => 'Demanda civil por incumplimiento de contrato', 'abogados' => ['2011112222', '2044445555']],
            ['numero_expediente' => 'EXP-2024-0002', 'cliente' => '1012345678', 'fecha_inicio' => '2024-03-02', 'fecha_finalizacion' => '2024-11-20', 'estado' => CasoEstado::Archivado, 'descripcion' => 'Reclamación de deuda', 'abogados' => ['2011112222']],
            ['numero_expediente' => 'EXP-2024-0003', 'cliente' => '1019876543', 'fecha_inicio' => '2024-02-10', 'fecha_finalizacion' => null, 'estado' => CasoEstado::EnTramite, 'descripcion' => 'Proceso penal por estafa', 'abogados' => ['2022223333']],
            ['numero_expediente' => 'EXP-2024-0004', 'cliente' => '1019876543', 'fecha_inicio' => '2024-05-18', 'fecha_finalizacion' => '2025-02-14', 'estado' => CasoEstado::Sentenciado, 'descripcion' => 'Accidente de tránsito', 'abogados' => ['2022223333']],
            ['numero_expediente' => 'EXP-2024-0005', 'cliente' => '1023456789', 'fecha_inicio' => '2024-04-25', 'fecha_finalizacion' => null, 'estado' => CasoEstado::EnTramite, 'descripcion' => 'Despido injustificado', 'abogados' => ['2033334444']],
            ['numero_expediente' => 'EXP-2024-0006', 'cliente' => '1023456789', 'fecha_inicio' => '2024-07-08', 'fecha_finalizacion' => '2024-12-10', 'estado' => CasoEstado::Desistido, 'descripcion' => 'Disputa de propiedad', 'abogados' => ['2033334444']],
            ['numero_expediente' => 'EXP-2025-0007', 'cliente' => '1034567890', 'fecha_inicio' => '2025-01-12', 'fecha_finalizacion' => null, 'estado' => CasoEstado::EnTramite, 'descripcion' => 'Constitución de sociedad', 'abogados' => ['2044445555']],
            ['numero_expediente' => 'EXP-2025-0008', 'cliente' => '1034567890', 'fecha_inicio' => '2025-03-30', 'fecha_finalizacion' => null, 'estado' => CasoEstado::Suspendido, 'descripcion' => 'Conflicto societario', 'abogados' => ['2044445555', '2011112222']],
            ['numero_expediente' => 'EXP-2025-0009', 'cliente' => '1045678901', 'fecha_inicio' => '2025-02-05', 'fecha_finalizacion' => '2025-10-22', 'estado' => CasoEstado::Archivado, 'descripcion' => 'Sucesión y herencia', 'abogados' => ['2011112222']],
            ['numero_expediente' => 'EXP-2025-0010', 'cliente' => '1045678901', 'fecha_inicio' => '2025-06-17', 'fecha_finalizacion' => null, 'estado' => CasoEstado::EnTramite, 'descripcion' => 'Cobro de honorarios profesionales', 'abogados' => ['2033334444']],
        ];
    }

    public function run(): void
    {
        foreach (self::datos() as $dato) {
            $cliente = Cliente::where('cedula', $dato['cliente'])->firstOrFail();

            $caso = Caso::updateOrCreate(
                ['numero_expediente' => $dato['numero_expediente']],
                [
                    'cliente_id' => $cliente->id,
                    'fecha_inicio' => $dato['fecha_inicio'],
                    'fecha_finalizacion' => $dato['fecha_finalizacion'],
                    'estado' => $dato['estado'],
                    'descripcion' => $dato['descripcion'],
                ],
            );

            $abogados = Abogado::whereIn('cedula', $dato['abogados'])->pluck('id');

            $caso->abogados()->sync($abogados->mapWithKeys(
                fn (int $abogadoId) => [$abogadoId => ['fecha_asignacion' => $dato['fecha_inicio']]],
            ));
        }
    }
}
