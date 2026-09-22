<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre'       => $this->faker->words(3, true),
            'precio'       => $this->faker->randomFloat(2, 100, 50000),
            'stock'        => $this->faker->numberBetween(10, 100),
        ];
    }
}
