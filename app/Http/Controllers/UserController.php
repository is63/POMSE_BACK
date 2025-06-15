<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use function Pest\Laravel\call;

class UserController extends Controller
{

    public function __construct() {}

    //index', 'show', 'edit', 'update', 'destroy
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        return view('users.create', compact('usuarios'));
    }

    public function store()
    {
        $table_name = 'users';
        $data = request()->validate([
            'usuario' => 'required|string|max:16|unique:users',
            'email' => 'required|email|unique:users',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif,webp',
            'password' => 'required|min:6',
            'is_admin' => 'boolean|nullable',
            'verificado' => 'boolean|nullable',
            'bio' => 'string|nullable',
        ]);

        $foto = request()->file('profile_image');
        if ($foto) {
            $data['foto'] = 'storage/' . $foto->store('imagenes', 'public');
        } else {
            $data['foto'] = null;
        }
        unset($data['profile_image']);

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
        $usuarios = DB::table('users')->get();

        return view('users.edit', compact('user', 'usuarios'));
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

        if ($data['password']) {
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
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }



    //-----------Funciones para la API ------------------------//


    public function allUsers()
    {
        $usuarios = User::all();
        return response()->json($usuarios, 200); // Return the users in JSON with 200 status
    }

    public function viewUser($id)
    {
        try {

            $usuario = User::findOrFail($id);
            return response()->json($usuario, 200); // Return the user in JSON with 200 status
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error al obtener el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function viewSelf()
    {
        try {
            $user = auth()->user();
            return response()->json($user, 200); // Return the user in JSON with 200 status
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error al obtener el usuario: ' . $e->getMessage()], 500);
        }
    }


    public function createUser()
    {
        $data = request()->validate([
            'usuario' => 'required|string|max:16|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'boolean|nullable',
            'verificado' => 'boolean|nullable',
        ]);
        $data['is_admin'] = isset($data['is_admin']) ? 1 : 0;
        $data['verificado'] = isset($data['verificado']) ? 1 : 0;
        $data['password'] = bcrypt($data['password']);
        $data['updated_at'] = now();
        $data['created_at'] = now();

        DB::table('users')->insert($data);
        return response()->json(['mensaje' => 'El usuario se ha creado correctamente'], 201); // Return the user in JSON with 201 status
    }

    public function editUser(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json(['mensaje' => 'No autenticado'], 401);
            }

            $validated = $request->validate([
                'usuario' => 'nullable|string|max:255|unique:users,usuario,' . $user->id,
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'bio' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);

            // Solo actualiza los campos enviados, los demás mantienen su valor anterior
            $user->usuario = $validated['usuario'] ?? $user->usuario;
            $user->email = $validated['email'] ?? $user->email;
            $user->bio = $validated['bio'] ?? $user->bio;

            if ($request->hasFile('foto')) {
                $user->foto = 'storage/' . $request->file('foto')->store('imagenes', 'public');
            }

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return response()->json(['mensaje' => 'Usuario editado con exito'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error al editar el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado exitosamente.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function searchUser(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255',
        ]);
        $usuarios = User::where('usuario', 'LIKE', '%' . $request->usuario . '%')->get()->take(10);

        if ($usuarios->isNotEmpty()) {
            return response()->json($usuarios, 200);
        } else {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }
    }
}
