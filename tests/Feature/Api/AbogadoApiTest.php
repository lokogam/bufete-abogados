<?php

namespace Tests\Feature\Api;

use App\Models\Abogado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AbogadoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_abogados_con_token(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Abogado::factory()->count(2)->create();

        $this->getJson('/api/abogados')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_lista_abogados_sin_token_devuelve_401(): void
    {
        $this->getJson('/api/abogados')->assertUnauthorized();
    }

    public function test_crea_un_abogado(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/abogados', [
            'cedula' => '987654321',
            'nombre' => 'Carlos',
            'apellido' => 'Ruiz',
            'especialidad' => 'Derecho Civil',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.especialidad', 'Derecho Civil');
    }

    public function test_crear_abogado_con_cedula_duplicada_devuelve_422(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Abogado::factory()->create(['cedula' => '987654321']);

        $this->postJson('/api/abogados', [
            'cedula' => '987654321',
            'nombre' => 'Carlos',
            'apellido' => 'Ruiz',
        ])->assertUnprocessable()->assertJsonValidationErrors('cedula');
    }

    public function test_muestra_un_abogado(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $abogado = Abogado::factory()->create();

        $this->getJson("/api/abogados/{$abogado->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $abogado->id);
    }

    public function test_actualiza_un_abogado(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $abogado = Abogado::factory()->create();

        $this->putJson("/api/abogados/{$abogado->id}", [
            'cedula' => $abogado->cedula,
            'nombre' => $abogado->nombre,
            'apellido' => $abogado->apellido,
            'especialidad' => 'Derecho Penal',
        ])->assertOk()->assertJsonPath('data.especialidad', 'Derecho Penal');
    }

    public function test_elimina_un_abogado_de_forma_logica(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $abogado = Abogado::factory()->create();

        $this->deleteJson("/api/abogados/{$abogado->id}")->assertOk();

        $this->assertSoftDeleted('abogados', ['id' => $abogado->id]);
    }
}
