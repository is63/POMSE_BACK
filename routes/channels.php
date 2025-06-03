<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log; // Importa Log para depuración
use App\Models\Chat; // Asegúrate de que el modelo Chat está importado

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Autorización para el canal de chat privado
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // --- Inicio: Logging para depuración ---
    Log::info('[Broadcasting Auth] Attempting to authorize user for chat channel.', [
        'user_id' => $user ? $user->id : 'Guest (User not available)',
        'channel' => 'chat.' . $chatId,
        'chat_id_param' => $chatId,
    ]);
    // --- Fin: Logging para depuración ---

    // $user ya es el usuario autenticado gracias al middleware 'auth.api'
    if (!$user) {
        Log::warning('[Broadcasting Auth] User not authenticated for channel chat.' . $chatId);
        return false; // Si por alguna razón $user es null, denegar.
    }

    $chat = Chat::find($chatId);

    if (!$chat) {
        Log::warning('[Broadcasting Auth] Chat not found for ID: ' . $chatId . ' for user ' . $user->id);
        return false; // El chat no existe
    }

    // Verifica si el usuario autenticado es uno de los participantes del chat
    if ($user->id == $chat->participante_1 || $user->id == $chat->participante_2) {
        Log::info('[Broadcasting Auth] User ' . $user->id . ' AUTHORIZED for chat.' . $chatId);
        // Puedes devolver true o un array con datos del usuario que se pasarán al frontend
        return ['id' => $user->id, 'name' => $user->usuario, 'foto' => $user->foto ? asset($user->foto) : null];
    }

    Log::warning('[Broadcasting Auth] User ' . $user->id . ' DENIED access to chat.' . $chatId . '. Chat participants: P1=' . $chat->participante_1 . ', P2=' . $chat->participante_2);
    return false; // El usuario no es participante
});

