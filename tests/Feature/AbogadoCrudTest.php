<?php

namespace Tests\Feature;

use App\Models\Abogado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbogadoCrudTest extends TestCase
{
    use RefreshDatabase;

    private function auth(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function test_lista_abogados(): void
    {
        $this->auth();
        $abogado = Abogado::factory()->create();

        $this->get('/abogados')
            ->assertOk()
            ->assertSee($abogado->nombre_completo);
    }

    public function test_muestra_formulario_de_creacion(): void
    {
        $this->auth();

        $this->get('/abogados/create')->assertOk()->assertSee('Nuevo abogado');
    }

    public function test_crea_un_abogado(): void
    {
        $this->auth();

        $response = $this->post('/abogados', [
            'cedula' => '987654321',
            'nombre' => 'Carlos',
            'apellido' => 'Ruiz',
            'email' => 'carlos@test.com',
            'telefono' => '3101234567',
            'especialidad' => 'Derecho Penal',
        ]);

        $response->assertRedirect(route('abogados.index'));

        $this->assertDatabaseHas('abogados', [
            'cedula' => '987654321',
            'especialidad' => 'Derecho Penal',
        ]);
    }

    public function test_crear_abogado_con_cedula_duplicada_falla(): void
    {
        $this->auth();
        Abogado::factory()->create(['cedula' => '987654321']);

        $this->post('/abogados', [
            'cedula' => '987654321',
            'nombre' => 'Carlos',
            'apellido' => 'Ruiz',
        ])->assertSessionHasErrors('cedula');
    }

    public function test_muestra_detalle_de_un_abogado(): void
    {
        $this->auth();
        $abogado = Abogado::factory()->create();

        $this->get("/abogados/{$abogado->id}")
            ->assertOk()
            ->assertSee($abogado->nombre_completo);
    }

    public function test_actualiza_un_abogado(): void
    {
        $this->auth();
        $abogado = Abogado::factory()->create();

        $response = $this->put("/abogados/{$abogado->id}", [
            'cedula' => $abogado->cedula,
            'nombre' => $abogado->nombre,
            'apellido' => $abogado->apellido,
            'especialidad' => 'Derecho Laboral',
        ]);

        $response->assertRedirect(route('abogados.show', $abogado));

        $this->assertDatabaseHas('abogados', [
            'id' => $abogado->id,
            'especialidad' => 'Derecho Laboral',
        ]);
    }

    public function test_elimina_un_abogado_de_forma_logica(): void
    {
        $this->auth();
        $abogado = Abogado::factory()->create();

        $response = $this->delete("/abogados/{$abogado->id}");

        $response->assertRedirect(route('abogados.index'));

        $this->assertSoftDeleted('abogados', ['id' => $abogado->id]);
    }
}
