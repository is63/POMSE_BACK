<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Message;
use App\Models\Post;
use App\Models\Saved;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear 15 usuarios
        User::factory(15)->create();

        // Crear 15 posts
        Post::factory(15)->create();

        // Crear 15 comentarios
        Comment::factory(15)->create();

        // Crear 15 amistades
        for ($i = 0; $i < 15; $i++) {
            try {
                Friendship::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 15 guardados
        for ($i = 0; $i < 15; $i++) {
            try {
                Saved::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 15 likes
        for ($i = 0; $i < 15; $i++) {
            try {
                Like::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 15 chats
        for ($i = 0; $i < 15; $i++) {
            try {
                Chat::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 15 mensajes
        Message::factory(15)->create();
    }
}
