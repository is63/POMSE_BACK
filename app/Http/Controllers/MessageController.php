<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController
{
    public function index()
    {
        $table_name = 'messages';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }
}
