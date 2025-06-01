<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Endpoint público
    public function publicData()
    {
        return response()->json(['message' => 'Este endpoint es público.'], 200);
    }

    // Endpoint protegido
    public function privateData(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'message' => 'Este endpoint es privado.',
            'user' => $user
        ], 200);
    }

    public function checkToken(Request $request)
    {
        try {
            // Intenta obtener el token con bearerToken()
            $token = $request->bearerToken();
            // Si falla, intenta extraerlo manualmente de la cabecera
            if (!$token) {
                $header = $request->header('Authorization');
                if ($header && stripos($header, 'Bearer ') === 0) {
                    $token = trim(substr($header, 7));
                }
            }
            if (!$token) {
                return response()->json(['message' => 'Token no proporcionado.'], 401);
            }
            $user = \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->authenticate();
            if ($user) {
                return response()->json(['message' => 'Token válido.', 'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Token inválido.'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token inválido o expirado.'], 401);
        }
    }
}
