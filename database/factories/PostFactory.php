<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
        return [
            'titulo' => fake()->text(10),
            'imagen' => fake()->randomElement(['storage/imagenes/uENGehY9MJm5IKC4Z4f7ilcG82WPf1I0xkHQbRRV.webp', 'storage/imagenes/a3LOoXtKLX2gMRQFx8LWoZduqv3qfEtUXHhUdODO.jpg','storage/imagenes/yhc5l4729h1f1.png',null]),
            'descripcion' => fake()->text(20),
            'usuario_id' => $usuario_id,
        ];
    }
}
