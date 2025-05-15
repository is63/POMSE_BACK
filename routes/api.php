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
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;



// Metodos de Registro y Login de la API 
Route::post('/apiLogin', [ApiAuthController::class, 'login']);
Route::post('/apiRegister', [ApiAuthController::class, 'register']);

//Se necesita el token del usuario para Cerrar sesion
Route::post('/apiLogout', [ApiAuthController::class, 'logout'])->middleware('auth:api');

// Ejemplo de endpoint público
Route::get('/public-data', [ApiController::class, 'publicData']);

//Rutas públicas

//Crear Usuario
Route::post('/users', [UserController::class, 'createUser']);

//Ver todos los Likes
Route::get('/likes', [LikeController::class, 'allLikes']);
Route::get('/likes/{usuario_id}', [LikeController::class, 'allLikesOfUser']); //Ver likes de un usuario

// Ejemplo de endpoint protegido Se necesita el token del usuario para acceder

//Rutas protegidas para el usuario Registrado/Logeado
Route::middleware('auth:api')->group(function () {
    Route::get('/private-data', [ApiController::class, 'privateData']);


    //Posts
    Route::get('/posts', [PostController::class, 'allPosts']);
    Route::get('/posts/{id}', [PostController::class, 'viewPost']);
    Route::post('/posts', [PostController::class, 'createPost']);

    //Comentarios
    Route::get('/comments', [CommentController::class, 'allComments']);
    Route::get('/comments/{id}', [CommentController::class, 'viewComment']);
    Route::post('/comments', [CommentController::class, 'createComment']);

    //Amistades 
    Route::post('/friendships', [FriendshipController::class, 'createFriendship']);

    //Likes
    Route::post('/likes', [LikeController::class, 'createLike']);

    //Guardados
    Route::post('/saveds', [SavedController::class, 'createSaved']);

    //Chats
    Route::get('/chatsOfUser', [ChatController::class, 'allChatsOfUser']);
    Route::post('/chats', [ChatController::class, 'createChat']);

    //Mensajes
    Route::get('/messages/{id}', [MessageController::class, 'allMessagesOfChat']);
    Route::post('/messages', [MessageController::class, 'createMessage']);
});

//Rutas protegidas para el administrador
Route::middleware(['auth:api', 'is_admin'])->group(function () {

    //Usuarios 
    Route::get('/users', [UserController::class, 'allUsers']);

    //Amistades
    Route::get('/friendships', [FriendshipController::class, 'allFriendships']);

    //Guardados
    Route::get('/saveds', [SavedController::class, 'allSaveds']);

    //Chats
    Route::get('/chats', [ChatController::class, 'allChats']);

    //Mensajes
    Route::get('/messages', [MessageController::class, 'allMessages']);

    
});

//Rutas protegidas para el administrador o el propietario
Route::middleware(['auth:api', 'owner_or_admin'])->group(function () {

    //Usuarios
    Route::post('/users/{id}', [UserController::class, 'viewUser']);
    Route::put('/users/{id}', [UserController::class, 'editUser']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

    //Posts
    //IMPORTANTE!!!!!!!!!! PARA QUE FUNCIONE EL PUT HAY QUE ENVARLO COMO POST Y AGREGAR UN CAMPO _method : PUT (al menos en postman)
    Route::put('/posts/{id}', [PostController::class, 'editPost']);
    Route::delete('/posts/{id}', [PostController::class, 'deletePost']);

    //Comentarios
    Route::put('/comments/{id}', [CommentController::class, 'editComment']); //Lo mismo que en Posts usar _method : PUT
    Route::delete('/comments/{id}', [CommentController::class, 'deleteComment']);

    //Amistades
    Route::get('/friendships/{usuario_id}', [FriendshipController::class, 'allFriendshipsByUser']);
    Route::get('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'viewFriendship']);
    Route::put('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'update']);
    Route::delete('/friendships/{usuario_id}/{amigo_id}', [FriendshipController::class, 'destroy']);

    //Likes
    Route::delete('/likes/{usuario_id}/{post_id}', [LikeController::class, 'deleteLike']);

    //Guardados
    Route::get('/savedsOfUser', [SavedController::class, 'allSavedsOfUser']);
    Route::delete('/saveds/{post_id}', [SavedController::class, 'deleteSaved']);

    //Chats
    Route::delete('/chats/{id}', [ChatController::class, 'deleteChat']);

    //Mensajes
    Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);
});
