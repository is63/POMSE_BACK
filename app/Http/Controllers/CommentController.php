<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController
{
    public function index()
    {
        $table_name = 'comments';
        $table_data = DB::table($table_name)->paginate(10);
        return view('comments.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        $posts = DB::table('posts')->get();
        return view('comments.create', compact('usuarios', 'posts'));
    }

    public function store()
    {
        $data = request()->validate([
            'texto' => 'required|string|max:500',
            'usuario_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'imagen' => 'nullable|image',
        ]);


        if (request()->hasFile('imagen')) {
            $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        }

        $data['updated_at'] = now();
        $data['created_at'] = now();


        DB::table('comments')->insert($data);

        return redirect()->route('comments.index')->with('success', 'Comentario creado exitosamente.');
    }

    public function edit($id)
    {
        $comment = DB::table('comments')->where('id', $id)->first();
        $usuarios = DB::table('users')->get();
        $posts = DB::table('posts')->get();
        if (!$comment) {
            return redirect()->route('comments.index')->with('error', 'Comentario no encontrado.');
        }
        return view('comments.edit', compact('comment', 'usuarios', 'posts'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'texto' => 'required|string|max:500',
            'usuario_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'imagen' => 'nullable|image',
        ]);

        $comment = DB::table('comments')->where('id', $id)->first();

        if (!$comment) {
            return redirect()->route('comments.index')->with('error', 'Comentario no encontrado.');
        }

        if (request()->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($comment->imagen && file_exists(public_path($comment->imagen))) {
                unlink(public_path($comment->imagen));
            }

            // Guardar la nueva imagen
            $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        } elseif (request()->input('delete_image')) {
            // Eliminar la imagen si se solicita
            if ($comment->imagen && file_exists(public_path($comment->imagen))) {
                unlink(public_path($comment->imagen));
            }
            $data['imagen'] = null;
        }

        $data['updated_at'] = now();

        DB::table('comments')->where('id', $id)->update($data);

        return redirect()->route('comments.index')->with('success', 'Comentario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        DB::table('comments')->where('id', $id)->delete();
        return redirect()->route('comments.index')->with('success', 'Comentario eliminado exitosamente.');
    }
}
