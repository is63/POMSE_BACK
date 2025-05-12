<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SavedController;

//Usuarios
Route::get('/users', [UserController::class, 'allUsers']);
Route::get('/users/{id}', [UserController::class, 'viewUser']);
Route::post('/users', [UserController::class, 'createUser']);
Route::put('/users/{id}', [UserController::class, 'editUser']);
Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

//Posts
Route::get('/posts', [PostController::class, 'allPosts']);
Route::get('/posts/{id}', [PostController::class, 'viewPost']);
Route::post('/posts', [PostController::class, 'createPost']);
Route::put('/posts/{id}', [PostController::class, 'editPost']);
Route::delete('/posts/{id}', [PostController::class, 'deletePost']);

//Comentarios
Route::get('/comments', [CommentController::class, 'allComments']);
Route::get('/comments/{id}', [CommentController::class, 'viewComment']);
Route::post('/comments', [CommentController::class, 'createComment']);
Route::put('/comments/{id}', [CommentController::class, 'editComment']);
Route::delete('/comments/{id}', [CommentController::class, 'deleteComment']);

//Amistades
Route::get('/friendships', [FriendshipController::class, 'allFriendships']);
Route::get('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'viewFriendship']);
Route::post('/friendships', [FriendshipController::class, 'createFriendship']);
Route::put('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'editFriendship']);
Route::delete('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'deleteFriendship']);

//Likes
Route::get('/likes/{usuario_id}', [LikeController::class, 'allLikes']); //Ver likes de un usuario
Route::post('/likes', [LikeController::class, 'createLike']);
Route::delete('/likes/{usuario_id}/{post_id}', [LikeController::class, 'deleteLike']);

//Saveds
Route::get('/saveds/{usuario_id}', [SavedController::class, 'allSaveds']);
Route::post('/saveds', [SavedController::class, 'createSaved']);
Route::delete('/saveds/{usuario_id}/{post_id}', [SavedController::class, 'deleteSaved']);

//Chats
Route::get('/chats/{usuario}', [ChatController::class, 'allChats']);
Route::post('/chats', [ChatController::class, 'createChat']);
Route::put('/chats/{id}', [ChatController::class, 'editChat']);
Route::delete('/chats/{id}', [ChatController::class, 'deleteChat']);

//Mensajes
Route::get('/messages/{chat_id}', [MessageController::class, 'allMessages']);
Route::post('/messages', [MessageController::class, 'createMessage']);
Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);




