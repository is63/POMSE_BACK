<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'admin') {
                abort(403, 'No autorizado');
            }
            return $next($request);
        });
    }

    //index', 'show', 'edit', 'update', 'destroy
    public function index()
    {

    }
}
