<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/register
     * Registra un nuevo usuario en la tienda y emite un token Bearer.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $usuario = Usuario::create([
            'nombre' => $request->string('nombre'),
            'email' => $request->string('email'),
            'password' => Hash::make($request->string('password')),
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente.',
            'data' => new UsuarioResource($usuario),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * POST /api/v1/auth/login
     * Valida credenciales y emite un token Bearer de acceso.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $usuario = Usuario::where('email', $request->string('email'))->first();

        if (! $usuario || ! Hash::check($request->string('password'), $usuario->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesion exitoso.',
            'data' => new UsuarioResource($usuario),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * GET /api/v1/auth/me
     * Retorna la informacion del usuario autenticado actual.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UsuarioResource($request->user()),
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     * Revoca (elimina) el token de acceso con el que se hizo la peticion.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesion cerrada exitosamente.',
        ]);
    }
}
