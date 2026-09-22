<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Obtener el identificador que se almacenará en el claim "sub" del JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retornar un arreglo clave-valor con claims personalizados para agregar al payload del JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'nombre' => $this->nombre,
            'email' => $this->email,
            'role' => 'cliente',
            'issued_at' => now()->toIso8601String(),
        ];
    }
}
