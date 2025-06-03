<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel; // Canal privado para usuarios autorizados
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message; // Asegúrate de importar el modelo Message si lo usas en el constructor

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message; // Tipado para mayor claridad

    public function __construct(Message $message) // Tipado del parámetro
    {
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel // Tipado del retorno
    {
        // Canal privado específico para el chat (solo participantes)
        return new PrivateChannel('chat.' . $this->message->chat_id);
    }

    public function broadcastWith(): array // Tipado del retorno
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'emisor_id' => $this->message->emisor_id,
            'receptor_id' => $this->message->receptor_id,
            'texto' => $this->message->texto,
            'imagen' => $this->message->imagen ? asset($this->message->imagen) : null, // Devuelve la URL completa si existe
            'created_at' => $this->message->created_at->toDateTimeString(),
            // Podrías incluir información del emisor si es útil para el frontend
            'emisor' => [
                'id' => $this->message->emisor->id,
                'usuario' => $this->message->emisor->usuario,
                'foto' => $this->message->emisor->foto ? asset($this->message->emisor->foto) : null,
            ]
        ];
    }
}
