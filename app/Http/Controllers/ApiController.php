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
        $user = $request->user();
        if ($user) {
            return response()->json(['message' => 'Token válido.', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Token inválido.'], 401);
        }
    }
}
