<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_registrarse_e_iniciar_sesion_con_jwt(): void
    {
        $responseRegister = $this->postJson('/api/v1/auth/register', [
            'nombre'   => 'Agustin Buss',
            'email'    => 'agustin@ejemplo.com',
            'password' => 'password123',
        ]);

        $responseRegister->assertStatus(201)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        $responseLogin = $this->postJson('/api/v1/auth/login', [
            'email'    => 'agustin@ejemplo.com',
            'password' => 'password123',
        ]);

        $responseLogin->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    public function test_rutas_protegidas_devuelven_401_sin_token(): void
    {
        $response = $this->getJson('/api/v1/carrito');

        $response->assertStatus(401);
    }

    public function test_permite_acceso_a_rutas_protegidas_con_token_valido(): void
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/v1/carrito');

        $response->assertStatus(200);
    }
}
