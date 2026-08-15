<?php

namespace Tests\Feature\Api;

use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_devuelve_token_bearer(): void
    {
        User::factory()->create(['email' => 'demo@test.com', 'password' => 'password']);

        $response = $this->postJson('/api/login', [
            'email' => 'demo@test.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'name', 'email']]);
    }

    public function test_login_con_credenciales_invalidas_devuelve_401(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'demo@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertUnauthorized();
    }

    public function test_caso_sin_token_devuelve_401(): void
    {
        $caso = Caso::factory()->create();

        $this->getJson("/api/casos/{$caso->id}")->assertUnauthorized();
    }

    public function test_caso_con_token_devuelve_toda_la_informacion(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();

        $caso = Caso::factory()->create(['cliente_id' => $cliente->id]);
        $caso->abogados()->attach($abogado->id, ['fecha_asignacion' => '2025-01-01']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/casos/{$caso->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $caso->id)
            ->assertJsonPath('data.numero_expediente', $caso->numero_expediente)
            ->assertJsonPath('data.estado.value', 'en_tramite')
            ->assertJsonPath('data.cliente.cedula', $cliente->cedula)
            ->assertJsonPath('data.abogados.0.cedula', $abogado->cedula)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'numero_expediente',
                    'estado' => ['value', 'label'],
                    'fecha_inicio',
                    'fecha_finalizacion',
                    'cliente' => ['id', 'cedula', 'nombre', 'apellido'],
                    'abogados',
                ],
            ]);
    }

    public function test_caso_inexistente_devuelve_404(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->getJson('/api/casos/999999')->assertNotFound();
    }

    public function test_soft_delete_no_elimina_registro_y_oculta_el_caso(): void
    {
        $user = User::factory()->create();
        $caso = Caso::factory()->create();

        $this->assertDatabaseCount('casos', 1);

        $caso->delete();

        $this->assertDatabaseCount('casos', 1);
        $this->assertNotNull($caso->fresh()->deleted_at);

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/casos/{$caso->id}")
            ->assertNotFound();
    }

    public function test_lista_casos_con_token(): void
    {
        $user = User::factory()->create();
        Caso::factory()->count(3)->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/casos')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_crea_un_caso_con_abogados_asignados(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/casos', [
                'numero_expediente' => 'EXP-2025-0100',
                'cliente_id' => $cliente->id,
                'fecha_inicio' => '2025-01-15',
                'estado' => 'en_tramite',
                'descripcion' => 'Demanda laboral',
                'abogados' => [$abogado->id],
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.numero_expediente', 'EXP-2025-0100')
            ->assertJsonPath('data.estado.value', 'en_tramite')
            ->assertJsonCount(1, 'data.abogados');

        $this->assertDatabaseCount('caso_abogado', 1);
    }

    public function test_crear_caso_con_estado_invalido_devuelve_422(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/casos', [
                'numero_expediente' => 'EXP-2025-0101',
                'cliente_id' => $cliente->id,
                'fecha_inicio' => '2025-01-15',
                'estado' => 'estado_invalido',
            ])->assertUnprocessable()->assertJsonValidationErrors('estado');
    }

    public function test_actualiza_un_caso_y_sus_abogados(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::factory()->create();
        $abogado = Abogado::factory()->create();
        $caso = Caso::factory()->create(['cliente_id' => $cliente->id]);
        $caso->abogados()->attach($abogado->id);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/casos/{$caso->id}", [
                'numero_expediente' => $caso->numero_expediente,
                'cliente_id' => $cliente->id,
                'fecha_inicio' => $caso->fecha_inicio->format('Y-m-d'),
                'estado' => 'archivado',
                'fecha_finalizacion' => $caso->fecha_inicio->copy()->addMonths(6)->format('Y-m-d'),
                'abogados' => [],
            ]);

        $response->assertOk()
            ->assertJsonPath('data.estado.value', 'archivado')
            ->assertJsonCount(0, 'data.abogados');

        $this->assertDatabaseCount('caso_abogado', 0);
    }

    public function test_elimina_un_caso_de_forma_logica(): void
    {
        $user = User::factory()->create();
        $caso = Caso::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/casos/{$caso->id}")
            ->assertOk();

        $this->assertSoftDeleted('casos', ['id' => $caso->id]);
    }
}
