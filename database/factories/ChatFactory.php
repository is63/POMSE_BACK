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
        $participante1 = DB::table('users')->pluck('id')->toArray();
        $participante1_id = fake()->randomElement($participante1);

        $participante2 = DB::table('users')->pluck('id')->toArray();
        $participante2 = array_diff($participante2, [$participante1_id]);
        $participante2_id = fake()->randomElement($participante2);


        return [
            'participante_1' => $participante1_id,
            'participante_2' => $participante2_id,
        ];
    }
}
