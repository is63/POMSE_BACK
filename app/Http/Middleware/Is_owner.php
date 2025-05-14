<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Is_owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeUserId = $request->route('id'); // Asume que el parámetro de ruta es 'id'

        if (!$user || $user->id != $routeUserId) {
            return response()->json(['error' => 'No tienes permisos para acceder a esta información'], 403);
        }
        return $next($request);
    }
}
