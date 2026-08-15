<?php

namespace Tests\Feature;

use App\Enums\CasoEstado;
use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasoCrudTest extends TestCase
{
    use RefreshDatabase;

    private function auth(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function test_lista_casos(): void
    {
        $this->auth();
        $caso = Caso::factory()->create();

        $this->get('/casos')
            ->assertOk()
            ->assertSee($caso->numero_expediente);
    }

    public function test_muestra_formulario_de_creacion_con_clientes_y_abogados(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();

        $this->get('/casos/create')
            ->assertOk()
            ->assertSee('Nuevo caso')
            ->assertSee($cliente->nombre_completo)
            ->assertSee($abogado->nombre_completo);
    }

    public function test_crea_un_caso_con_abogados_asignados(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();
        $abogado1 = Abogado::factory()->create();
        $abogado2 = Abogado::factory()->create();

        $response = $this->post('/casos', [
            'numero_expediente' => 'EXP-2025-0001',
            'cliente_id' => $cliente->id,
            'fecha_inicio' => '2025-01-15',
            'estado' => CasoEstado::EnTramite->value,
            'descripcion' => 'Demanda laboral',
            'abogados' => [$abogado1->id, $abogado2->id],
        ]);

        $response->assertRedirect(route('casos.index'));

        $this->assertDatabaseHas('casos', [
            'numero_expediente' => 'EXP-2025-0001',
            'cliente_id' => $cliente->id,
        ]);

        $this->assertDatabaseCount('caso_abogado', 2);
    }

    public function test_crear_caso_con_cliente_inexistente_falla(): void
    {
        $this->auth();

        $this->post('/casos', [
            'numero_expediente' => 'EXP-2025-0002',
            'cliente_id' => 9999,
            'fecha_inicio' => '2025-01-15',
            'estado' => CasoEstado::EnTramite->value,
        ])->assertSessionHasErrors('cliente_id');
    }

    public function test_muestra_detalle_de_un_caso(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();
        $caso = Caso::factory()->create(['cliente_id' => $cliente->id]);
        $caso->abogados()->attach($abogado->id, ['fecha_asignacion' => '2025-01-15']);

        $this->get("/casos/{$caso->id}")
            ->assertOk()
            ->assertSee($caso->numero_expediente)
            ->assertSee($cliente->nombre_completo)
            ->assertSee($abogado->nombre_completo);
    }

    public function test_actualiza_un_caso_y_sus_abogados(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();
        $caso = Caso::factory()->create(['cliente_id' => $cliente->id]);
        $caso->abogados()->attach($abogado->id);

        $response = $this->put("/casos/{$caso->id}", [
            'numero_expediente' => $caso->numero_expediente,
            'cliente_id' => $cliente->id,
            'fecha_inicio' => $caso->fecha_inicio->format('Y-m-d'),
            'estado' => CasoEstado::Archivado->value,
            'fecha_finalizacion' => $caso->fecha_inicio->copy()->addMonths(6)->format('Y-m-d'),
            'abogados' => [],
        ]);

        $response->assertRedirect(route('casos.show', $caso));

        $this->assertDatabaseHas('casos', [
            'id' => $caso->id,
            'estado' => CasoEstado::Archivado->value,
        ]);

        $this->assertDatabaseCount('caso_abogado', 0);
    }

    public function test_elimina_un_caso_de_forma_logica(): void
    {
        $this->auth();
        $caso = Caso::factory()->create();

        $response = $this->delete("/casos/{$caso->id}");

        $response->assertRedirect(route('casos.index'));

        $this->assertSoftDeleted('casos', ['id' => $caso->id]);
    }

    public function test_busca_casos_por_estado(): void
    {
        $this->auth();
        $archivado = Caso::factory()->archivado()->create();
        $tramite = Caso::factory()->create();

        $this->get('/casos?q=archivado')
            ->assertOk()
            ->assertSee($archivado->numero_expediente)
            ->assertDontSee($tramite->numero_expediente);
    }
}
