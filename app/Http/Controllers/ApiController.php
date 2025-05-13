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
}
