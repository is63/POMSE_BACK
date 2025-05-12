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

    public function allComments()
    {
        $comments = DB::table('comments')->get();
        return response()->json($comments);
    }

    public function viewComment($id)
    {
        $comment = DB::table('comments')->where('id', $id)->first();
        if ($comment) {
            return response()->json($comment);
        } else {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        }
    }

    public function createComment()
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

        return response()->json(['success' => 'Comentario creado exitosamente'], 201);
    }

    public function editComment($id)
    {
        $data = request()->validate([
            'texto' => 'required|string|max:500',
            'usuario_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'imagen' => 'nullable|image',
        ]);

        $comment = DB::table('comments')->where('id', $id)->first();

        if (!$comment) {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
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

        return response()->json(['success' => 'Comentario actualizado exitosamente'], 200);
    }

    public function deleteComment($id)
    {
        $comment = DB::table('comments')->where('id', $id)->first();
        if ($comment) {
            // Eliminar la imagen si existe
            if ($comment->imagen && file_exists(public_path($comment->imagen))) {
                unlink(public_path($comment->imagen));
            }
            DB::table('comments')->where('id', $id)->delete();
            return response()->json(['success' => 'Comentario eliminado exitosamente'], 200);
        } else {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        }
    }
}
