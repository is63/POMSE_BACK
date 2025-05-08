<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $emisor = DB::table('users')->pluck('id')->toArray();
        $emisor_id = fake()->randomElement($emisor);

        $receptor = DB::table('users')->pluck('id')->toArray();
        $receptor = array_diff($receptor, [$emisor_id]);
        $receptor_id = fake()->randomElement($receptor);

        $chats = DB::table('chats')->pluck('id')->toArray();
        $chat_id = fake()->randomElement($chats);
        return [
            'texto' => $this->faker->text(20),
            'imagen' => $this->faker->imageUrl(),
            'emisor_id' => $emisor_id,
            'receptor_id' => $receptor_id,
            'chat_id' => $chat_id,
        ];
    }
}
