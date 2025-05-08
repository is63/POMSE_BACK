<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usuarios = \App\Models\User::pluck('id')->toArray();
        $usuario_id = fake()->randomElement($usuarios);

        $posts = \App\Models\Post::pluck('id')->toArray();
        $post_id = fake()->randomElement($posts);

        return [
            'usuario_id' => $usuario_id,
            'post_id' => $post_id,
            'saved_at' => now(),
        ];
    }
}
