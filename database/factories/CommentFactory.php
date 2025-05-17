<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {  
    
        $usuarios = DB::table('users')->pluck('id')->toArray();
        $usuario_id = fake()->randomElement($usuarios);

        $posts = DB::table('posts')->pluck('id')->toArray();
        $post_id = fake()->randomElement($posts);

        return [
            'texto' => fake()->text(20),
            'imagen' => fake()->imageUrl(),
            'usuario_id' => $usuario_id,
            'post_id' => $post_id,
        ];

    }
}
