<?php
// database/factories/MessageFactory.php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        // Selecciona un chat existente
        $chat = Chat::inRandomOrder()->first();
        if (!$chat) {
            // Si no hay chats, crea uno
            $chat = Chat::factory()->create();
        }
        // Los participantes del chat
        $emisor = $this->faker->randomElement([$chat->participante_1, $chat->participante_2]);
        $receptor = ($emisor == $chat->participante_1) ? $chat->participante_2 : $chat->participante_1;
        return [
            'emisor_id' => $emisor,
            'receptor_id' => $receptor,
            'chat_id' => $chat->id,
            'texto' => $this->faker->realText(40),
            'imagen' => null,
        ];
    }
}
