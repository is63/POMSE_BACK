<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavedController
{
    public function index()
    {
        $table_name = 'saveds';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }
}
