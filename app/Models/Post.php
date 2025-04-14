<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'titulo', 'foto', 'descripcion', 'usuario_id'
    ];

    public function user_listing(): BelongsTo
    {
        return $this->belongsTo(User_Listing::class, 'usuario_id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function savedByPost()
    {
            return $this->belongsToMany(User_Listing::class, 'saveds', 'post_id', 'usuario_id');
    }

    public function likeByPost()
    {
        return $this->belongsToMany(User_Listing::class, 'likes', 'post_id', 'usuario_id');
    }
}
