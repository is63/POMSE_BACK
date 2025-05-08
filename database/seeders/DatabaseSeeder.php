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
    User::factory(10)->create();
    Post::factory(10)->create();
    Comment::factory(10)->create();
    for($i = 0; $i < 10; $i++){
        try{Friendship::factory()->create();}
        catch (\Exception $e){}
    }
    for($i = 0; $i < 10; $i++) {
        try {
            Saved::factory()->create();
        }
        catch (\Exception $e){}
        }
    for($i = 0; $i < 10; $i++){
        try {
            Like::factory()->create();
        }
        catch (\Exception $e){}
    }
    for ($i = 0; $i < 10; $i++){
        try {
            Friendship::factory()->create();
        }
        catch (\Exception $e){}
    }
    for ($i = 0; $i < 10; $i++){
        try {
            Chat::factory()->create();
        }
        catch (\Exception $e){}
    }

    Message::factory(10)->create();
    }
}
