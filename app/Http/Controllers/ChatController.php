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

    //--------------------Funciones API--------------------//

    public function allChats()
    {
        try {
            $chats = DB::table('chats')->get();
            return response()->json($chats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los chats: ' . $e->getMessage()], 500);
        }
    }

    public function allChatsOfUser()
    {
        try {
            $userId = auth()->id();
            $chats = DB::table('chats')
                ->where('participante_1', $userId)
                ->orWhere('participante_2', $userId)
                ->get();
            return response()->json($chats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los chats: ' . $e->getMessage()], 500);
        }
    }
    public function createChat()
    {
        try {
            $userId = auth()->id(); // Participante 1 será el usuario autenticado

            $data = request()->validate([
                'participante_2' => 'required|exists:users,id',
            ]);
            $data['participante_1'] = $userId;

            // Verificar si los participantes son el mismo usuario
            if ($data['participante_1'] === $data['participante_2']) {
                return response()->json(['error' => 'Los participantes no pueden ser el mismo usuario'], 400);
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
                return response()->json(['error' => 'El chat ya existe entre estos dos usuarios'], 400);
            }
            $data['updated_at'] = now();
            $data['created_at'] = now();

            DB::table('chats')->insert($data);
            return response()->json(['mensaje' => 'Chat creado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el chat: ' . $e->getMessage()], 500);
        }
    }

    public function deleteChat($id)
    {
        try {
            $deleted = DB::table('chats')->where('id', $id)->delete();
            if ($deleted) {
                return response()->json(['mensaje' => 'Chat eliminado correctamente']);
            } else {
                return response()->json(['error' => 'No se encontró el chat para eliminar'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el chat: ' . $e->getMessage()], 500);
        }
    }
}
