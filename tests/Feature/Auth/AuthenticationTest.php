<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_login_se_muestra_correctamente(): void
    {
        $this->get('/login')->assertOk()->assertSee('Iniciar sesión');
    }

    public function test_la_pagina_de_registro_se_muestra_correctamente(): void
    {
        $this->get('/register')->assertOk()->assertSee('Crear cuenta');
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        User::factory()->create([
            'email' => 'demo@test.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'demo@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_con_credenciales_invalidas_no_autentica(): void
    {
        $response = $this->post('/login', [
            'email' => 'nadie@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_usuario_puede_registrarse(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario Nuevo',
            'email' => 'nuevo@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.com']);
    }

    public function test_el_registro_valida_la_contrasena(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario Nuevo',
            'email' => 'nuevo@test.com',
            'password' => 'corta',
            'password_confirmation' => 'corta',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_un_usuario_puede_cerrar_sesion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_las_rutas_del_sistema_requieren_autenticacion(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/clientes')->assertRedirect(route('login'));
        $this->get('/abogados')->assertRedirect(route('login'));
        $this->get('/casos')->assertRedirect(route('login'));
    }
}
