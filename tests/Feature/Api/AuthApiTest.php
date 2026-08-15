<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_devuelve_token_y_crea_usuario(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.com']);
    }

    public function test_register_con_email_duplicado_devuelve_422(): void
    {
        User::factory()->create(['email' => 'existente@test.com']);

        $this->postJson('/api/register', [
            'name' => 'Nuevo Usuario',
            'email' => 'existente@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_logout_revoca_el_token_actual(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/logout')->assertOk()->assertJson(['message' => 'Sesión cerrada correctamente.']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_logout_sin_token_devuelve_401(): void
    {
        $this->postJson('/api/logout')->assertUnauthorized();
    }

    public function test_el_endpoint_user_devuelve_el_usuario_autenticado(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }
}
