<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User_Listing extends Model
{

    protected $table = 'users_listing';

    protected $fillable = [
      'usuario','email', 'contrasena', 'bio', 'foto'
    ];

    public function post(): HasMany
    {
        return $this->hasMany(Post::class,'usuario_id');
    }

    public function comment(): HasManymany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
