<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteCrudTest extends TestCase
{
    use RefreshDatabase;

    private function auth(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function test_lista_clientes(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();

        $this->get('/clientes')
            ->assertOk()
            ->assertSee($cliente->nombre_completo);
    }

    public function test_muestra_formulario_de_creacion(): void
    {
        $this->auth();

        $this->get('/clientes/create')->assertOk()->assertSee('Nuevo cliente');
    }

    public function test_crea_un_cliente(): void
    {
        $this->auth();

        $response = $this->post('/clientes', [
            'cedula' => '123456789',
            'nombre' => 'Laura',
            'apellido' => 'Pérez',
            'email' => 'laura@test.com',
            'telefono' => '3001234567',
            'direccion' => 'Calle 1',
        ]);

        $response->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', [
            'cedula' => '123456789',
            'email' => 'laura@test.com',
        ]);
    }

    public function test_crear_cliente_con_cedula_duplicada_falla(): void
    {
        $this->auth();
        Cliente::factory()->create(['cedula' => '123456789']);

        $this->post('/clientes', [
            'cedula' => '123456789',
            'nombre' => 'Laura',
            'apellido' => 'Pérez',
        ])->assertSessionHasErrors('cedula');
    }

    public function test_muestra_detalle_de_un_cliente(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();

        $this->get("/clientes/{$cliente->id}")
            ->assertOk()
            ->assertSee($cliente->nombre_completo)
            ->assertSee($cliente->cedula);
    }

    public function test_actualiza_un_cliente(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();

        $response = $this->put("/clientes/{$cliente->id}", [
            'cedula' => $cliente->cedula,
            'nombre' => 'Nuevo',
            'apellido' => 'Nombre',
            'email' => 'actualizado@test.com',
        ]);

        $response->assertRedirect(route('clientes.show', $cliente));

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombre' => 'Nuevo',
            'apellido' => 'Nombre',
        ]);
    }

    public function test_elimina_un_cliente_de_forma_logica(): void
    {
        $this->auth();
        $cliente = Cliente::factory()->create();

        $response = $this->delete("/clientes/{$cliente->id}");

        $response->assertRedirect(route('clientes.index'));

        $this->assertSoftDeleted('clientes', ['id' => $cliente->id]);
    }

    public function test_busca_clientes_por_nombre(): void
    {
        $this->auth();
        $ana = Cliente::factory()->create(['nombre' => 'Ana', 'apellido' => 'López']);
        $luis = Cliente::factory()->create(['nombre' => 'Luis', 'apellido' => 'Pérez']);

        $this->get('/clientes?q=ana')
            ->assertOk()
            ->assertSee('Ana López')
            ->assertDontSee('Luis Pérez');
    }

    public function test_pagina_el_listado_de_clientes(): void
    {
        $this->auth();
        Cliente::factory()->count(15)->create();

        $this->get('/clientes')
            ->assertOk()
            ->assertSee('page=2');
    }
}
