<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendshipController
{
    public function index()
    {
        $table_name = 'friendships';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        return view('friendships.create', compact('usuarios'));
    }

    public function store()
    {
        try {
            $table_name = 'friendships';
            $data = request()->validate([
                'usuario_id' => 'required|integer|exists:users,id',
                'amigo_id' => 'required|integer|exists:users,id',
                'accepted' => 'boolean|nullable',
            ]);

            $data['accepted'] = isset($data['accepted']) ? 1 : 0;
            $data['updated_at'] = now();
            $data['created_at'] = now();

            //dd($data);

            DB::table($table_name)->insert($data);
            return redirect()->route('friendships.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('friendships.index')->with('error', 'Error al crear la amistad: ' . $e->getMessage());
        }
    }

    public function edit($usuario_id, $amigo_id)
    {

        $friendship = Friendship::where('usuario_id', $usuario_id)
            ->where('amigo_id', $amigo_id)
            ->firstOrFail();

        $usuarios = User::all();


        return view('friendships.edit', compact('friendship', 'usuarios'));
    }

    public function update($usuario_id, $amigo_id)
    {
        try {
            // Validar y actualizar los datos
            $data = request()->validate([
                'accepted' => 'nullable|boolean',
            ]);

            // Encuentra la amistad por sus claves primarias
            $updated = DB::table('friendships')
                ->where('usuario_id', '=', $usuario_id)
                ->where('amigo_id', '=', $amigo_id)
                ->update([
                    'accepted' => isset($data['accepted']) ? 1 : 0,
                    'updated_at' => now(),
                ]);

            // Verificar si se actualizó
            if ($updated === 0) {
                throw new \Exception('No se encontró la amistad para actualizar.');
            }

            return redirect()->route('friendships.index')->with('success', 'Amistad actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('friendships.index')->with('error', 'Error al actualizar la amistad: ' . $e->getMessage());
        }
    }


    public function destroy($usuario_id, $amigo_id)
    {
        try {
            // Eliminar la amistad de la base de datos
            $deleted = DB::table('friendships')
                ->where('usuario_id', '=', $usuario_id)
                ->where('amigo_id', '=', $amigo_id)
                ->delete();

            // Verificar si la eliminación fue exitosa
            if ($deleted === 0) {
                return redirect()->route('friendships.index')->with('error', 'La amistad no fue eliminada.');
            }

            return redirect()->route('friendships.index')->with('success', 'Amistad eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('friendships.index')->with('error', 'Error al eliminar la amistad: ' . $e->getMessage());
        }
    }


}
