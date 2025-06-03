<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            if (
                !in_array($request->emisor_id, [$chat->participante_1, $chat->participante_2]) ||
                !in_array($request->receptor_id, [$chat->participante_1, $chat->participante_2])
            ) {
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

    public function update($id, Request $request)
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
            if (
                !in_array($request->emisor_id, [$chat->participante_1, $chat->participante_2]) ||
                !in_array($request->receptor_id, [$chat->participante_1, $chat->participante_2])
            ) {
                return redirect()->back()->with('error', 'El emisor o el receptor no están en el chat seleccionado.');
            }

            $message = Message::findOrFail($id);

            if ($request->hasFile('imagen')) {
                $data['imagen'] = 'storage/' . $request->file('imagen')->store('imagenes', 'public');
            } else {
                $data['imagen'] = $message->imagen;
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
            Storage::disk('public')->delete($message->imagen);
        }
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Mensaje eliminado exitosamente.');
    }

    //--------------Funciones para API----------------//
    public function allMessages()
    {
        try {
            $messages = DB::table('messages')->get();
            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los mensajes: ' . $e->getMessage()], 500);
        }
    }

    public function allMessagesOfChat($chat_id)
    {
        try {

            $messages = DB::table('messages')->where('chat_id', $chat_id)->orderBy('created_at', 'asc')->get();
            return response()->json($messages);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los mensajes: ' . $e->getMessage()], 500);
        }
    }

    public function createMessage()
{
    try {
        $data = request()->validate([
            'chat_id' => 'required|exists:chats,id',
            'texto' => 'required|string|max:500',
            'imagen' => 'nullable|image',
        ]);

        $emisor_id = auth()->id();

        $chat = DB::table('chats')->where('id', $data['chat_id'])->first();

        if (!$chat) {
            return response()->json(['error' => 'No existe el chat especificado'], 404);
        }

        if ($chat->participante_1 == $emisor_id) {
            $receptor_id = $chat->participante_2;
        } elseif ($chat->participante_2 == $emisor_id) {
            $receptor_id = $chat->participante_1;
        } else {
            return response()->json(['error' => 'No perteneces a este chat'], 403);
        }

        $insertData = [
            'chat_id' => $chat->id,
            'emisor_id' => $emisor_id,
            'receptor_id' => $receptor_id,
            'texto' => $data['texto'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (request()->hasFile('imagen')) {
            $insertData['imagen'] = 'storage/' . request()->file('imagen')->store('imagenes', 'public');
        }

        // Guarda el mensaje usando el modelo Message para mejor manejo
        $message = Message::create($insertData);

        // Emitir evento Pusher para notificar a los participantes
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['mensaje' => 'Mensaje guardado correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al guardar el mensaje: ' . $e->getMessage()], 500);
    }
}
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            if ($message->imagen) {
                // Eliminar la imagen del almacenamiento
                \Storage::disk('public')->delete($message->imagen);
            }
            $message->delete();
            return response()->json(['mensaje' => 'Mensaje eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el mensaje: ' . $e->getMessage()], 500);
        }
    }
}
