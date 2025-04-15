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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saveds', 'post_id', 'usuario_id')
            ->withPivot('saved_at')
            ->withTimestamps();
    }

    public function likeByPost()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'usuario_id')
            ->withPivot('saved_at');
    }
}
