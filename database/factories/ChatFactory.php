<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $participante_1 = fake()->randomElement($users);
        $participante_2 = fake()->randomElement(array_diff($users, [$participante_1]));

        return [
            'participante_1' => $participante_1,
            'participante_2' => $participante_2,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
