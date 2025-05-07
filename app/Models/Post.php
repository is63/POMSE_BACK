<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';

    protected $fillable = [
        'titulo', 'imagen', 'descripcion', 'usuario_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->titulo) && empty($post->imagen) && empty($post->descripcion)) {
                throw new \Exception('El post debe tener al menos un título, una imagen o una descripción.');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function savedByUsers()
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
