<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController
{
    public function index()
    {
        $table_name = 'likes';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create(Request $request)
    {
        $usuarios = DB::table('users')->get();

        $posts = DB::table('posts')->get();

        return view('likes.create', compact('usuarios', 'posts'));
    }

    public function store()
    {
        $data = request()->validate([
            'usuario_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);


        DB::table('likes')->insert($data);
        return redirect()->route('likes.index')->with('success', 'Like asignado exitosamente.');
    }

    public function destroy($usuario_id, $post_id)
    {
        DB::table('likes')->where('usuario_id', $usuario_id)->where('post_id', $post_id)->delete();
        return redirect()->route('likes.index')->with('success', 'Like eliminado exitosamente.');
    }
}
