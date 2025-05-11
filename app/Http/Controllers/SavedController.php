<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavedController
{
    public function index()
    {
        $table_name = 'saveds';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        $posts = DB::table('posts')->get();
        return view('saveds.create', compact('usuarios', 'posts'));
    }

    public function store()
    {
        try {
            $table_name = 'saveds';
            $data = request()->validate([
                'usuario_id' => 'required|integer|exists:users,id',
                'post_id' => 'required|integer|exists:users,id',
            ]);
            $data['saved_at'] = now();

            DB::table($table_name)->insert($data);
            return redirect()->route('saveds.index')->with('success', 'Post guadado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('saveds.index')->with('error', 'Error al guardar el post: ' . $e->getMessage());
        }
    }

    public function destroy($usuario_id, $post_id)
    {
        try {
            // Eliminar la amistad de la base de datos
            $deleted = DB::table('saveds')
                ->where('usuario_id', '=', $usuario_id)
                ->where('post_id', '=', $post_id)
                ->delete();

            // Verificar si la eliminación fue exitosa
            if ($deleted === 0) {
                return redirect()->route('saveds.index')->with('error', 'El post guardado no fue eliminada.');
            }

            return redirect()->route('saveds.index')->with('success', 'Post guardado eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('saveds.index')->with('error', 'Error al eliminar el post guardado: ' . $e->getMessage());
        }
    }
}
