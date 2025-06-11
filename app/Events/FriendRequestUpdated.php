<?php
// app/Events/FriendRequestUpdated.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class FriendRequestUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $userId;
    public $data; // Puedes incluir aquí la info relevante (opcional)

    public function __construct($userId, $data = [])
    {
        $this->userId = $userId;
        $this->data = $data;
    }

    // Canal privado para el usuario destinatario
    public function broadcastOn()
    {
        return new PrivateChannel('friend-requests.' . $this->userId);
    }

    // Nombre del evento que escucharás en Vue
    public function broadcastAs()
    {
        return 'FriendRequestUpdated';
    }
}