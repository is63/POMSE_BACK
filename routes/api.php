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
Route::get('/check-token', [ApiController::class, 'checkToken']);

//Rutas públicas
Route::get('/posts', action: [PostController::class, 'allPosts']);
Route::get('/posts/{id}', [PostController::class, 'viewPost']);
Route::get('viewPostOfUser', [PostController::class, 'viewPostOfUser']);

Route::get('/comments', [CommentController::class, 'allComments']);
Route::get('/commentsOfPost/{id}', [CommentController::class, 'commentsOfPost']);

//Crear Usuario
Route::post('/users', [UserController::class, 'createUser']);
Route::get('/users/{id}', [UserController::class, 'viewUser']);
Route::get('/users', [UserController::class, 'allUsers']);


//Ver todos los Likes
Route::get('/likes', [LikeController::class, 'allLikes']);
Route::get('/likesOfUser', [LikeController::class, 'allLikesOfUser']); //Ver likes de un usuario
Route::get('/likesOfPost/{id}', [LikeController::class, 'likesOfPost']); //Ver likes de un post

Route::get('/savedsOfPost/{post_id}', [SavedController::class, 'allSavedsOfPost']);
// Ejemplo de endpoint protegido Se necesita el token del usuario para acceder

//Rutas protegidas para el usuario Registrado/Logeado
Route::middleware('auth:api')->group(function () {
    Route::get('/private-data', [ApiController::class, 'privateData']);

    //Usuarios
    Route::get('/viewUser', [UserController::class, 'viewSelf']);
    Route::get('/searchUser', [UserController::class, 'searchUser']);

    //Posts
    Route::post('/posts', [PostController::class, 'createPost']);

    //Comentarios
    Route::get('/comments/{id}', [CommentController::class, 'viewComment']);
    Route::post('/comments', [CommentController::class, 'createComment']);

    //Amistades 
    Route::get('/friendshipsOfUser', [FriendshipController::class, 'allFriendshipsOfUser']);
    Route::post('/friendships', [FriendshipController::class, 'createFriendship']);
    Route::put('/acceptFriendship', [FriendshipController::class, 'acceptFriendship']);
    Route::delete('/declineFriendship', [FriendshipController::class, 'declineFriendship']);


    //Likes
    Route::post('/likes', [LikeController::class, 'createLike']);
    Route::delete('/likes', [LikeController::class, 'deleteMyLike']);

    //Guardados
    Route::post('/saveds', [SavedController::class, 'createSaved']);
    Route::delete('/saveds/{post_id}', [SavedController::class, 'deleteSaved']);


    //Chats y Mensajes en tiempo real con WebSockets
    Route::get('/chatsOfUser', [ChatController::class, 'allChatsOfUser']);
    Route::post('/chats', [ChatController::class, 'createChat']);
    Route::delete('/chats/{id}', [ChatController::class, 'deleteChat']);

    Route::get('/messages/{id}', [MessageController::class, 'allMessagesOfChat']);
    Route::post('/messages', [MessageController::class, 'createMessage']);
    Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);
});

//Rutas protegidas para el administrador
Route::middleware(['auth:api', 'is_admin'])->group(function () {

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
    Route::put('/users', [UserController::class, 'editUser']);
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
    Route::delete('/friendships', [FriendshipController::class, 'deleteFriendship']);

    //Likes
    Route::delete('/likes/{usuario_id}/{post_id}', [LikeController::class, 'deleteLike']);

    //Guardados
    Route::get('/savedsOfUser', [SavedController::class, 'allSavedsOfUser']);

    //Chats
    Route::delete('/chats/{id}', [ChatController::class, 'deleteChat']);

    //Mensajes
    Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);
});
