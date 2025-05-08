<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Saved>
 */
class SavedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usuarios = \DB::table('users')->pluck('id')->toArray();
        $usuario_id = fake()->randomElement($usuarios);

        $posts = \DB::table('posts')->pluck('id')->toArray();
        $post_id = fake()->randomElement($posts);
        return [
            'usuario_id' => $usuario_id,
            'post_id' => $post_id,
            'saved_at' => now(),
        ];
    }
}
