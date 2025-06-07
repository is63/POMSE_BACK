<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'is_group' => 'sometimes|boolean',
            'participantes' => 'required|array|size:2',
            'participantes.*' => 'exists:users,id',
        ]);
        $data['is_group'] = false; // Solo 2 participantes, no es grupo

        // Si los selects de participantes vienen por separado, aseguramos que no sean iguales
        if ($data['participantes'][0] == $data['participantes'][1]) {
            return redirect()->back()->withInput()->with('error', 'Los participantes deben ser diferentes.');
        }

        $chat = Chat::create([
            'name' => $data['name'] ?? null,
            'is_group' => false,
        ]);
        $chat->users()->sync($data['participantes']);

        return redirect()->route('chats.index')->with('success', 'Chat creado correctamente.');
    }

    public function edit($id)
    {
        $usuarios = DB::table('users')->get();
        $chat = Chat::find($id);
        if (!$chat) {
            return redirect()->route('chats.index')->with('error', 'Chat no encontrado.');
        }
        // Obtener los IDs de los participantes actuales
        $participantes = $chat->users()->pluck('users.id')->toArray();
        return view('chats.edit', compact('chat', 'usuarios', 'participantes'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'is_group' => 'sometimes|boolean',
            'participantes' => 'required|array|min:2',
            'participantes.*' => 'exists:users,id',
        ]);
        $data['is_group'] = $request->has('is_group');
        $data['updated_at'] = now();
        $chat = Chat::findOrFail($id);
        $chat->update([
            'name' => $data['name'],
            'is_group' => $data['is_group'],
            'updated_at' => $data['updated_at'],
        ]);
        // Sincronizar los participantes
        $chat->users()->sync($data['participantes']);
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
        $userId = Auth::id();

        $user = Auth::user(); // Obtener el usuario autenticado

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Obtener los chats del usuario, incluyendo los usuarios participantes y el último mensaje
        $chats = $user->chats() // Usa la relación definida en el modelo User
            ->with(['users' => function ($query) use ($user) {
                // Cargar los otros participantes del chat, excluyendo al usuario actual
                // Esto es útil para obtener fácilmente al "otro" usuario en un chat 1-a-1
                $query->where('users.id', '!=', $user->id);
            }])
            ->withLastMessage() // Usa el scope para cargar el último mensaje y su timestamp
            ->orderBy('last_message_at', 'desc') // Ordenar los chats por el más reciente
            ->get();

        // Formatear la respuesta para que sea más útil para el frontend
        $formattedChats = $chats->map(function ($chat) use ($user) {
            $otherUser = null;
            // Si no es un grupo y hay otros usuarios en la relación 'users' (que ya excluye al actual)
            if (!$chat->is_group && $chat->users->isNotEmpty()) {
                $otherUser = $chat->users->first();
            }

            $lastMessage = $chat->messages->first(); // El scope withLastMessage ya carga esto

            return [
                'id' => $chat->id,
                // 'name' => $chat->is_group ? $chat->name : ($otherUser ? $otherUser->usuario : 'Chat con usuario desconocido'),
            ];
        });

        return response()->json($formattedChats);
    }
    public function createChat(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1', // IDs de los usuarios a añadir al chat
            'user_ids.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
            'is_group' => 'boolean',
        ]);

        $currentUserId = Auth::id();
        $participantIds = array_unique(array_merge([$currentUserId], $request->user_ids));

        if (count($participantIds) < 2) {
            return response()->json(['message' => 'A chat must have at least two participants.'], 400);
        }

        $isGroup = $request->boolean('is_group') || count($participantIds) > 2; // Si hay más de 2, es grupo

        // Lógica para encontrar un chat existente (si es DM) o crear uno nuevo
        if (!$isGroup && count($participantIds) == 2) {
            // Es un DM, buscar si ya existe un chat entre estos dos usuarios
            $chat = Chat::where('is_group', false)
                ->whereHas('users', function ($query) use ($participantIds) {
                    $query->whereIn('users.id', $participantIds);
                }, '=', count($participantIds)) // Asegura que solo estos 2 usuarios estén en el chat
                ->first();

            if ($chat) {
                return response()->json(['message' => 'Chat already exists', 'chat' => $chat->load('users')], 200);
            }
        }


        $chat = Chat::create([
            'name' => $request->name,
            'is_group' => $isGroup,
        ]);
        $chat->users()->sync($participantIds);

        return response()->json(['chat' => $chat->load('users')], 201);
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

    public function show($id)
    {
        $chat = Chat::with(['users', 'messages.user'])->findOrFail($id);
        return response()->json($chat);
    }
}
