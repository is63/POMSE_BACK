<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController
{
    public function index()
    {
        $table_name = 'chats';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name .'.index', compact('table_name', 'table_data'));
    }

    public function store(Request $request)
    {
        // Aquí puedes manejar la lógica para almacenar un nuevo mensaje
        // Por ejemplo, guardar el mensaje en la base de datos
    }

    public function show($id)
    {
        // Aquí puedes manejar la lógica para mostrar un mensaje específico
    }

    public function destroy($id)
    {
        // Aquí puedes manejar la lógica para eliminar un mensaje
    }
}
