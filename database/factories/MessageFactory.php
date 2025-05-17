<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $chat = Chat::inRandomOrder()->first();

        // Si no hay chats, crea uno con dos usuarios
        if (!$chat) {
            $users = User::inRandomOrder()->take(2)->pluck('id');
            $chat = Chat::create([
                'participante_1' => $users[0],
                'participante_2' => $users[1],
            ]);
        }

        $emisor_id = fake()->randomElement([$chat->participante_1, $chat->participante_2]);
        $receptor_id = $emisor_id == $chat->participante_1 ? $chat->participante_2 : $chat->participante_1;

        return [
            'texto' => $this->faker->text(20),
            'imagen' => $this->faker->imageUrl(),
            'emisor_id' => $emisor_id,
            'receptor_id' => $receptor_id,
            'chat_id' => $chat->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
