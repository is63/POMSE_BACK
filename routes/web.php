<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SavedController;


// Si no se esta Autorizado no se puede acceder
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $databaseName = DB::getDatabaseName();
        $tables = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', $databaseName)
            ->get();

        // Borrar las tablas que se crean por defecto para que no aparezcan en la vista
        $tables = array_slice($tables->toArray(), 2);
        array_splice($tables, 5, 2);

        return view('welcome', compact('tables'));
    });

    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('comments', CommentController::class);

    Route::get('likes', [LikeController::class, 'index'])->name('likes.index');
    Route::get('likes/create', [LikeController::class, 'create']);
    Route::post('likes', [LikeController::class, 'store']);
    Route::delete('likes/{usuario_id}/{post_id}', [LikeController::class, 'destroy']);

    Route::get('saveds', [SavedController::class, 'index'])->name('saveds.index');
    Route::get('saveds/create', [SavedController::class, 'create']);
    Route::post('saveds', [SavedController::class, 'store']);
    Route::delete('saveds/{usuario_id}/{post_id}', [SavedController::class, 'destroy']);

    Route::get('friendships', [FriendshipController::class, 'index'])->name('friendships.index');
    Route::get('friendships/create', [FriendshipController::class, 'create']);
    Route::post('friendships', [FriendshipController::class, 'store']);
    Route::get('friendships/{usuario_id}/{amigo_id}/edit', [FriendshipController::class, 'edit']);
    Route::put('friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'update']);
    Route::delete('friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'destroy']);

    Route::get('/chats/{chat}/participants', [MessageController::class, 'getChatParticipants']);
    Route::resource('messages', MessageController::class);
    Route::resource('chats', ChatController::class);
});


//Rutas para la autorizacion

Route::get('register', [AuthController::class, 'create']);
Route::post('register', [AuthController::class, 'store']);

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'storeLogin']);

Route::post('logout', [AuthController::class, 'destroy']);
