<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Registro de usuario e inicio de sesión con JWT.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $usuario = Usuario::create([
            'nombre'   => $request->validated('nombre'),
            'email'    => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $token = JWTAuth::fromUser($usuario);

        return response()->json([
            'message'      => 'Usuario registrado exitosamente.',
            'data'         => new UsuarioResource($usuario),
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60, // Expiración en segundos
        ], 201);
    }

    /**
     * Autenticación de usuario y generación de JWT.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas.'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Obtener el perfil del usuario autenticado vía JWT.
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'data' => new UsuarioResource(auth('api')->user())
        ], 200);
    }

    /**
     * Cerrar sesión (Invalidar el token JWT actual).
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.'
        ], 200);
    }

    /**
     * Estructurar la respuesta del token JWT con expiración y metadatos.
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60, // Expiración en segundos
            'user'         => new UsuarioResource(auth('api')->user())
        ], 200);
    }
}
