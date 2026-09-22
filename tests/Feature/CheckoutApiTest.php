<?php

namespace Tests\Feature;

use App\Models\CarritoItem;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_procesa_checkout_exitosamente_descuenta_stock_y_vacia_carrito(): void
    {
        // 1. Crear usuario
        $user = User::factory()->create();

        // 2. Crear categoría y producto
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create([
            'categoria_id' => $categoria->id,
            'stock'        => 10,
            'precio'       => 1000,
        ]);

        // 3. Crear ítem en el carrito
        CarritoItem::create([
            'usuario_id'  => $user->id,
            'producto_id' => $producto->id,
            'cantidad'    => 2,
        ]);

        // 4. Ejecutar la petición del Checkout incluyendo usuario_id
        $response = $this->actingAs($user, 'api')
            ->postJson('/api/v1/checkout', [
                'usuario_id'    => $user->id,
                'direccion'     => 'Av. Corrientes 1234',
                'ciudad'        => 'Buenos Aires',
                'codigo_postal' => 'C1043',
                'metodo_pago'   => 'tarjeta_credito',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Compra procesada y confirmada con exito.');

        // 5. Verificar descuento de stock en la BD
        $this->assertDatabaseHas('productos', [
            'id'    => $producto->id,
            'stock' => 8,
        ]);

        // 6. Verificar que se limpió el carrito del usuario
        $this->assertDatabaseMissing('carrito_items', [
            'usuario_id' => $user->id,
        ]);
    }
}
