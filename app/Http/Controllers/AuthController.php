<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //_----Metodos del Registro
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        try {
            // validar los datos
            request()->validate([
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed']
            ]);

            User::create([
                'usuario' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
            ]);

            //redirect
            return redirect('/');
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }
    }


    //----------METODOS DE LOGIN
    public function login()
    {
        return view('auth.login');
    }

    public function storeLogin()
    {
        //validar
        $attibutes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = DB::table('users')->where('email', $attibutes['email'])->first();
        // Comprobar si el usuario existe
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'El email o la contraseña no son correctos'
            ]);
        }
        //verificar si el usuario es admin
        if (!$user->is_admin) {
            throw ValidationException::withMessages([
                'email' => 'El usuario no tiene permisos para acceder a esta pagina'
            ]);
        }

        //intentar logear
        if (!Auth::attempt($attibutes)) {
            throw ValidationException::withMessages([
                'email' => 'El email o la contraseña no son correctos'
            ]);
        }

        //regenerar la sesion
        request()->session()->regenerate();
        //redireccionar
        return redirect('/')->with('success', 'Bienvenido de nuevo');
    }

    //Metodos de Logout
    public
    function destroy()
    {
        Auth::logout();

        return redirect('/login');
    }
}
