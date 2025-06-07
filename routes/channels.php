<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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

// Canal privado para un chat específico
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Solo permite si el usuario está autenticado y es participante del chat
    $chat = Chat::find($chatId);
    if (!$chat) return false;

    return $chat->users->contains($user->id);
}, ['middleware' => ['auth:api']]);
