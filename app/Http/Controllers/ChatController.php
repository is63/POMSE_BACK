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
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        return view('chats.create', compact('usuarios'));
    }

    public function store()
    {
        $data = request()->validate([
            'participante_1' => 'required|exists:users,id',
            'participante_2' => 'required|exists:users,id',
        ]);

        // Verificar si los participantes son el mismo usuario
        if ($data['participante_1'] === $data['participante_2']) {
            return redirect()->back()->with('error', 'Los participantes no pueden ser el mismo usuario.');
        }
        // Verificar si el chat ya existe
        $existingChat = DB::table('chats')
            ->where(function ($query) use ($data) {
                $query->where('participante_1', $data['participante_1'])
                    ->where('participante_2', $data['participante_2']);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('participante_1', $data['participante_2'])
                    ->where('participante_2', $data['participante_1']);
            })
            ->first();
        if ($existingChat) {
            return redirect()->back()->with('error', 'El chat ya existe entre estos dos usuarios.');
        }

        $data['updated_at'] = now();
        $data['created_at'] = now();

        DB::table('chats')->insert($data);
        return redirect()->route('chats.index')->with('success', 'Chat creado exitosamente.');
    }

    public function edit($id)
    {
        $usuarios = DB::table('users')->get();
        $chat = DB::table('chats')->where('id', $id)->first();
        if (!$chat) {
            return redirect()->route('chats.index')->with('error', 'Chat no encontrado.');
        }
        return view('chats.edit', compact('chat', 'usuarios'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'participante_1' => 'required|exists:users,id',
            'participante_2' => 'required|exists:users,id',
        ]);

        // Verificar si los participantes son el mismo usuario
        if ($data['participante_1'] === $data['participante_2']) {
            return redirect()->back()->with('error', 'Los participantes no pueden ser el mismo usuario.');
        }

        $existingChat = DB::table('chats')
            ->where(function ($query) use ($data) {
                $query->where('participante_1', $data['participante_1'])
                    ->where('participante_2', $data['participante_2']);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('participante_1', $data['participante_2'])
                    ->where('participante_2', $data['participante_1']);
            })
            ->first();
        if ($existingChat) {
            return redirect()->back()->with('error', 'El chat ya existe entre estos dos usuarios.');
        }

        $data['updated_at'] = now();
        DB::table('chats')->where('id', $id)->update($data);
        return redirect()->route('chats.index')->with('success', 'Chat actualizado exitosamente.');
    }

    public function destroy($id)
    {

        Db::table('chats')->where('id', $id)->delete();
        return redirect()->route('chats.index')->with('success', 'Chat eliminado exitosamente.');
    }
}
