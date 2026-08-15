<?php

namespace Tests\Feature\Export;

use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class ExportarCasosCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_comando_genera_excel_con_una_hoja_por_abogado(): void
    {
        Storage::fake('local');

        $cliente = Cliente::factory()->create();
        $abogado1 = Abogado::factory()->create(['nombre' => 'Juan', 'apellido' => 'Pérez']);
        $abogado2 = Abogado::factory()->create(['nombre' => 'Ana', 'apellido' => 'Rodríguez']);

        $caso = Caso::factory()->create(['cliente_id' => $cliente->id]);
        $caso->abogados()->attach([$abogado1->id, $abogado2->id]);

        $this->artisan('casos:export', ['--nombre' => 'casos_test.xlsx'])
            ->expectsOutputToContain('casos_test.xlsx')
            ->assertSuccessful();

        Storage::disk('local')->assertExists('casos_test.xlsx');

        $zip = new ZipArchive;
        $zip->open(Storage::disk('local')->path('casos_test.xlsx'));

        $workbook = $zip->getFromName('xl/workbook.xml');
        $this->assertIsString($workbook);
        $this->assertStringContainsString('Juan Pérez', $workbook);
        $this->assertStringContainsString('Ana Rodríguez', $workbook);

        $zip->close();
    }
}
