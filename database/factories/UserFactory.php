<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * El modelo correspondiente a esta factory.
     */
    protected $model = User::class;

    /**
     * Contraseña por defecto cacheada para optimizar los tests.
     */
    protected static ?string $password;

    /**
     * Define el estado por defecto del modelo User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'   => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
