<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saved extends Model
{
    use HasFactory;

    protected $table = 'saveds';

    protected $fillable = ['usuario_id', 'post_id'];

    public $timestamps = false;

}
