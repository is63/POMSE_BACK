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
        $amigo_id = fake()->randomElement(array_diff($usuarios, [$usuario_id]));

        return [
            'usuario_id' => $usuario_id,
            'amigo_id' => $amigo_id,
            'accepted' => fake()->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
