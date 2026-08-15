<?php

namespace Tests\Feature\Api;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClienteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_clientes_con_token(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Cliente::factory()->count(3)->create();

        $this->getJson('/api/clientes')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_lista_clientes_sin_token_devuelve_401(): void
    {
        $this->getJson('/api/clientes')->assertUnauthorized();
    }

    public function test_crea_un_cliente(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/clientes', [
            'cedula' => '123456789',
            'nombre' => 'Laura',
            'apellido' => 'Pérez',
            'email' => 'laura@test.com',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.cedula', '123456789')
            ->assertJsonPath('data.nombre_completo', 'Laura Pérez');
    }

    public function test_crear_cliente_con_cedula_duplicada_devuelve_422(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Cliente::factory()->create(['cedula' => '123456789']);

        $this->postJson('/api/clientes', [
            'cedula' => '123456789',
            'nombre' => 'Laura',
            'apellido' => 'Pérez',
        ])->assertUnprocessable()->assertJsonValidationErrors('cedula');
    }

    public function test_muestra_un_cliente(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $cliente = Cliente::factory()->create();

        $this->getJson("/api/clientes/{$cliente->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $cliente->id);
    }

    public function test_actualiza_un_cliente(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $cliente = Cliente::factory()->create();

        $this->putJson("/api/clientes/{$cliente->id}", [
            'cedula' => $cliente->cedula,
            'nombre' => 'Nuevo',
            'apellido' => 'Nombre',
        ])->assertOk()->assertJsonPath('data.nombre', 'Nuevo');
    }

    public function test_elimina_un_cliente_de_forma_logica(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $cliente = Cliente::factory()->create();

        $this->deleteJson("/api/clientes/{$cliente->id}")->assertOk();

        $this->assertSoftDeleted('clientes', ['id' => $cliente->id]);
    }

    public function test_mostrar_cliente_inexistente_devuelve_404(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/clientes/9999')->assertNotFound();
    }
}
