<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

    Route::get('/table/{table}', function ($table) {
        $databaseName = DB::getDatabaseName();
        $table_data = DB::table($table)->paginate(5);
        return view('table', compact(['table', 'table_data']));
    });
});


Route::resource('users', UserController::class);



//Rutas para la autorizacion

Route::get('register',[AuthController::class, 'create']);
Route::post('register',[AuthController::class, 'store']);

Route::get('login',[AuthController::class, 'login'])->name('login');
Route::post('login',[AuthController::class, 'storeLogin']);

Route::post('logout',[AuthController::class, 'destroy']);
