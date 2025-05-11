<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;
use App\Models\Chat;

class MessageController
{
    public function index()
    {
        $table_name = 'messages';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        $chats = DB::table('chats')->get();
        return view('messages.create', compact('usuarios', 'chats'));
    }

    public function getChatParticipants($chatId)
    {
        $chat = Chat::find($chatId);

        if (!$chat) {
            return response()->json(['error' => 'Chat no encontrado'], 404);
        }
        $usuarios = User::whereIn('id', [$chat->participante_1, $chat->participante_2])->get();

        return response()->json([
            'participants' => $usuarios
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = request()->validate([
                'emisor_id' => 'required|exists:users,id',
                'receptor_id' => 'required|exists:users,id',
                'chat_id' => 'required|exists:chats,id',
                'texto' => 'required|string|max:500',
                'imagen' => 'nullable|image',
            ]);

            // Verificar si el emisor y el receptor son el mismo usuario
            if ($data['emisor_id'] === $data['receptor_id']) {
                return redirect()->back()->with('error', 'El emisor y el receptor no pueden ser el mismo usuario.');
            }

            // Verificar si los usuarios pertenecen al chat seleccionado
            $chat = Chat::find($request->chat_id);
            if (!in_array($request->emisor_id, [$chat->participante_1, $chat->participante_2]) ||
                !in_array($request->receptor_id, [$chat->participante_1, $chat->participante_2])) {
                return redirect()->back()->with('error', 'El emisor o el receptor no están en el chat seleccionado.');
            }

            if (request()->hasFile('imagen')) {
                $data['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
            }

            $data['updated_at'] = now();
            $data['created_at'] = now();

            DB::table('messages')->insert($data);
            return redirect()->route('messages.index')->with('success', 'Mensaje creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el mensaje: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        $usuarios = DB::table('users')->get();
        $chats = DB::table('chats')->get();

        return view('messages.edit', compact('message', 'usuarios', 'chats'));
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'emisor_id' => 'required|exists:users,id',
                'receptor_id' => 'required|exists:users,id',
                'chat_id' => 'required|exists:chats,id',
                'texto' => 'required|string|max:500',
                'imagen' => 'nullable|image',
            ]);

            // Verificar si el emisor y el receptor son el mismo usuario
            if ($data['emisor_id'] === $data['receptor_id']) {
                return redirect()->back()->with('error', 'El emisor y el receptor no pueden ser el mismo usuario.');
            }

            // Verificar si los usuarios pertenecen al chat seleccionado
            $chat = Chat::find($request->chat_id);
            if (!in_array($request->emisor_id, [$chat->participante_1, $chat->participante_2]) ||
                !in_array($request->receptor_id, [$chat->participante_1, $chat->participante_2])) {
                return redirect()->back()->with('error', 'El emisor o el receptor no están en el chat seleccionado.');
            }

            $message = Message::findOrFail($id);

            if ($request->hasFile('imagen')) {
                $data['imagen'] = 'storage/' . $request->file('imagen')->store('imagenes', 'public');
            } else {
                $data['imagen'] = $message->imagen; // Mantener la imagen existente si no se sube una nueva
            }

            $data['updated_at'] = now();

            $message->update($data);

            return redirect()->route('messages.index')->with('success', 'Mensaje actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el mensaje: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        if ($message->imagen) {
            // Eliminar la imagen del almacenamiento
            \Storage::disk('public')->delete($message->imagen);
        }
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Mensaje eliminado exitosamente.');
    }
}
