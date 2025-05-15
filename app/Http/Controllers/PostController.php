<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

use Illuminate\Support\Facades\Storage;

class PostController
{
    public function index()
    {
        $table_name = 'posts';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        return view('posts.create', compact('usuarios'));
    }

    public function store()
    {
        $table_name = 'posts';
        $data = request()->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'file|nullable',
            'descripcion' => 'string|nullable',
            'usuario_id' => 'required|integer|exists:users,id',
        ]);

        if (request()->hasFile('imagen')) {
            $data['imagen'] = request()->file('imagen')->store('imagenes', 'public');
            $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        }

        $data['updated_at'] = now();
        $data['created_at'] = now();

        //dd($data);
        DB::table($table_name)->insert($data);
        return redirect()->route('posts.index')->with('success', 'Post creado exitosamente.');
    }

    public function edit($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();
        $usuarios = DB::table('users')->get();
        return view('posts.edit', compact('post', 'usuarios'));
    }

    public function update($id)
    {
        $table_name = 'posts';
        $data = request()->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'file|nullable',
            'descripcion' => 'string|nullable',
            'usuario_id' => 'required|integer|exists:users,id',
        ]);

        $post = Post::findOrFail($id);
        if (request()->hasFile('imagen')) {
            $data['imagen'] = request()->file('imagen')->store('imagenes', 'public');
            $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        }

        $data['updated_at'] = now();

        //dd($data);

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post eliminado exitosamente.');
    }


    //--------------Funciones para API----------------//
    public function allPosts()
    {
        try {
            $posts = Post::all();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los posts: ' . $e->getMessage()], 500);
        }
    }

    public function viewPost($id)
    {
        try {
            $post = DB::table('posts')->where('id', $id)->firstOrFail();
            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el post: ' . $e->getMessage()], 500);
        }
    }

    public function createPost()
    {
        $data = request()->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'file|nullable',
            'descripcion' => 'string|nullable',
        ]);

        $data['usuario_id'] = auth()->id();

        if (request()->hasFile('imagen')) {
            $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        }

        $data['updated_at'] = now();
        $data['created_at'] = now();

        DB::table('posts')->insert($data);

        return response()->json(['message' => 'Post creado exitosamente.'], 201);
    }

    public function editPost($id)
    {
        try {
            $request = request();

            $data = $request->validate([
                'titulo' => 'string|max:255',
                'imagen' => 'file|nullable',
                'descripcion' => 'string|nullable',
            ]);

            $post = Post::findOrFail($id);

                //Si hay una imagen nueva, eliminar la anterior
            if ($request->hasFile('imagen')) {
                if ($post->imagen && Storage::disk('public')->exists(str_replace('storage/', '', $post->imagen))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $post->imagen));
                }
                $data['imagen'] = 'storage/' . $request->file('imagen')->store('imagenes', 'public');
            }

            if (!isset($data['titulo'])) {
                $data['titulo'] = $post->titulo;
            }

            $data['updated_at'] = now();

            $post->update($data);

            return response()->json(['message' => 'Post actualizado exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el post: ' . $e->getMessage()], 500);
        }
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post eliminado exitosamente.'], 200);
    }
}
