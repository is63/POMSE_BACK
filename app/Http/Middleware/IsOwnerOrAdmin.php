<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsOwnerOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeUserId = $request->route('id');

        if (!$user || ($user->id != $routeUserId && !$user->is_admin)) {
            return response()->json(['error' => 'No tienes permisos para acceder a esta información'], 403);
        }
        return $next($request);
    }
}
