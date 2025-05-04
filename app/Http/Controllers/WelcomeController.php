<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $databaseName = DB::getDatabaseName();
        $tables = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', $databaseName)
            ->get();
        //Borrar las tablas que se crean por defecto para que no aparezcan en la vista
        $tables = array_slice($tables->toArray(), 2);
        array_splice($tables, 5, 2);
//        dd($tables);
        return view('welcome', compact('tables'));
    }
}
