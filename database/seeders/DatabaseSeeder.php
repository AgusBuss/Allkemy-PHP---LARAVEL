<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba
        User::firstOrCreate(
            ['email' => 'agustin@ejemplo.com'],
            [
                'name' => 'Agustin Buss',
                'password' => bcrypt('password123'),
            ]
        );

        // Categoría sin descripcion
        $categoria = Categoria::firstOrCreate([
            'nombre' => 'Electrónica',
        ]);

        // Producto sin descripcion
        Producto::firstOrCreate([
            'id' => 1,
        ], [
            'categoria_id' => $categoria->id,
            'nombre'       => 'Producto de Prueba',
            'precio'       => 1000.00,
            'stock'        => 50,
        ]);
    }
}
