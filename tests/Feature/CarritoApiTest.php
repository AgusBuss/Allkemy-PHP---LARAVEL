<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_puede_agregar_producto_al_carrito(): void
    {
        // 1. Crear el usuario persistido en DB
        $user = User::factory()->create();

        // 2. Crear categoría y producto vinculados explícitamente
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create([
            'categoria_id' => $categoria->id,
            'stock'        => 10,
        ]);

        // 3. Autenticar al usuario para el guard 'api'
        $response = $this->actingAs($user, 'api')
            ->postJson('/api/v1/carrito', [
                'producto_id' => $producto->id,
                'cantidad'    => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Producto agregado al carrito con exito.');

        $this->assertDatabaseHas('carrito_items', [
            'usuario_id'  => $user->id,
            'producto_id' => $producto->id,
            'cantidad'    => 2,
        ]);
    }
}
