<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendship>
 */
class FriendshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usuarios = User::pluck('id')->toArray();
        $usuario_id = fake()->randomElement($usuarios);

        $amigos = User::pluck('id')->toArray();
        $amigos = array_diff($amigos, [$usuario_id]);
        $amigo_id = fake()->randomElement($amigos);
        return [
            'usuario_id' => $usuario_id,
            'amigo_id' => $amigo_id,
            'accepted' => fake()->randomElement([true,false]),
        ];
    }
}
