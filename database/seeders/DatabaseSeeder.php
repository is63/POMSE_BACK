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
        // Crear 30 usuarios
        User::factory(30)->create();

        // Crear 30 posts
        Post::factory(30)->create();

        // Crear 30 comentarios
        Comment::factory(30)->create();

        // Crear 30 amistades
        for ($i = 0; $i < 30; $i++) {
            try {
                Friendship::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 30 guardados
        for ($i = 0; $i < 30; $i++) {
            try {
                Saved::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 30 likes
        for ($i = 0; $i < 30; $i++) {
            try {
                Like::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 30 chats
        for ($i = 0; $i < 30; $i++) {
            try {
                Chat::factory()->create();
            } catch (\Exception $e) {
            }
        }

        // Crear 30 mensajes
        Message::factory(30)->create();
    }
}
