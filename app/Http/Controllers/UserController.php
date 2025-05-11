<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function __construct()
    {
    }

    //index', 'show', 'edit', 'update', 'destroy
    public function index()
    {
        $table_name = 'users';
        $table_data = DB::table($table_name)->paginate(10);
        return view('users.index', compact('table_name', 'table_data'));
    }
    public function create()
    {

        return view('users.create');
    }

    public function store()
    {
        $table_name = 'users';
        $data = request()->validate([
            'usuario' => 'required|string|max:16|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'boolean|nullable',
            'verificado' => 'boolean|nullable',
            'bio' => 'string|nullable',
        ]);

        $data['is_admin'] = isset($data['is_admin']) ? 1 : 0;
        $data['verificado'] = isset($data['verificado']) ? 1 : 0;
        $data['password'] = bcrypt($data['password']);
        $data['updated_at'] = now();
        $data['created_at'] = now();

        //dd($data);
        DB::table($table_name)->insert($data);
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'usuario' => 'required|string|max:16',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
            'is_admin' => 'boolean|nullable',
            'verificado' => 'boolean|nullable',
        ]);

        $user = User::findOrFail($id);

        if($data['password']){
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        $data['is_admin'] = isset($data['is_admin']) ? 1 : 0;
        $data['verificado'] = isset($data['verificado']) ? 1 : 0;
        $data['updated_at'] = now();

        //dd($data);

        $user->update([
            'usuario' => $data['usuario'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => $data['is_admin'],
            'verificado' => $data['verificado'],
            'updated_at' => $data['updated_at'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

}
