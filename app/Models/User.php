<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Nombre de la tabla en la base de datos.
     */
    protected $table = 'usuarios';

    /**
     * Los atributos que se pueden asignar de forma masiva.
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    /**
     * Los atributos ocultos en serializaciones.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casteos de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Obtiene el identificador que se almacenará en el claim del sujeto JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna un arreglo con clave-valor conteniendo claims personalizados a agregar al JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
