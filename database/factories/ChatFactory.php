<?php
// database/factories/ChatFactory.php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition()
    {
        // Selecciona dos usuarios distintos
        $userIds = User::pluck('id')->toArray();
        if (count($userIds) < 2) {
            // Si no hay suficientes usuarios, crea dos
            $userIds = [User::factory()->create()->id, User::factory()->create()->id];
        }
        $participantes = $this->faker->randomElements($userIds, 2);
        return [
            'participante_1' => $participantes[0],
            'participante_2' => $participantes[1],
        ];
    }
}
