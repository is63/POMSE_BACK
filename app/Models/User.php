<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'usuario', 'email', 'password', 'bio', 'foto'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('El email introducido no tiene un formato válido.');
            }
        });

        static::updating(function ($user) {
            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('El email introducido no tiene un formato válido.');
            }
        });
    }

    public function post(): HasMany
    {
        return $this->hasMany(Post::class, 'usuario_id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function savePost(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'saveds', 'usuario_id', 'post_id')
            ->withPivot('saved_at');
    }

    public function likePost(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'usuario_id', 'post_id')
            ->withPivot('saved_at');
    }

}
