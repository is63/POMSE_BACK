<?php

namespace App\Http\Controllers;

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
    public function edit($id)
    {
        // Aquí puedes manejar la lógica para editar un mensaje específico
    }
    public function update(Request $request, $id)
    {
        // Aquí puedes manejar la lógica para actualizar un mensaje específico
    }
    public function create()
    {
        // Aquí puedes manejar la lógica para crear un nuevo mensaje
    }

}
