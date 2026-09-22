<?php

namespace Tests\Unit;

use App\DTOs\ResumenCompraData;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumenCompraDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_calcula_correctamente_subtotal_impuestos_y_total_del_carrito(): void
    {
        $producto1 = Producto::factory()->create(['precio' => 1000.00, 'stock' => 10]);
        $producto2 = Producto::factory()->create(['precio' => 2000.00, 'stock' => 10]);

        $item1 = new CarritoItem(['cantidad' => 2]);
        $item1->setRelation('producto', $producto1);

        $item2 = new CarritoItem(['cantidad' => 1]);
        $item2->setRelation('producto', $producto2);

        $items = collect([$item1, $item2]);

        $resumen = ResumenCompraData::fromCarrito($items);

        // Subtotal: (2 * 1000) + (1 * 2000) = 4000
        $this->assertEquals(4000.00, $resumen->subtotal);
        // Impuestos (21% IVA): 4000 * 0.21 = 840
        $this->assertEquals(840.00, $resumen->impuestos);
        // Costo Envio: 500
        $this->assertEquals(500.00, $resumen->costoEnvio);
        // Total: 4000 + 840 + 500 = 5340
        $this->assertEquals(5340.00, $resumen->total);
    }
}
