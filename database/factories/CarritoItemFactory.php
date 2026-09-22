<?php

namespace Database\Factories;

use App\Models\CarritoItem;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarritoItemFactory extends Factory
{
    protected $model = CarritoItem::class;

    public function definition(): array
    {
        return [
            'usuario_id'  => User::factory(),
            'producto_id' => Producto::factory(),
            'cantidad'    => $this->faker->numberBetween(1, 5),
        ];
    }
}
