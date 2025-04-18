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


    //------------ Funciones de Amistad -------------------------- //
    public function solicitudesEnviadas()
    {
        return $this->belongsToMany(User::class, 'friendships', 'usuario_id', 'amigo_id')
            ->withPivot('friend')
            ->withTimestamps();
    }

// Usuarios que me enviaron solicitud
    public function solicitudesRecibidas()
    {
        return $this->belongsToMany(User::class, 'friendships', 'amigo_id', 'usuario_id')
            ->withPivot('friend')
            ->withTimestamps();
    }

// Amigos confirmados (simetrico)
    public function friends()
    {
        $enviadas = $this->solicitudesEnviadas()->wherePivot('friend', true)->get();
        $recibidas = $this->solicitudesRecibidas()->wherePivot('friend', true)->get();

        return $enviadas->merge($recibidas);
    }
    public function enviarSolicitud(User $usuario)
    {

        if($this->id == $usuario->id) {
            throw new error('no se puede enviar una solicitud a si mismo'); // No se puede enviar solicitud a uno mismo
        }

        $id1 = min($this->id, $usuario->id);
        $id2 = max($this->id, $usuario->id);

        // Verificar si ya existe
        $existe = DB::table('friendships')
            ->where('usuario_id', $id1)
            ->where('amigo_id', $id2)
            ->exists();

        if (!$existe) {
            DB::table('friendships')->insert([
                'usuario_id' => $this->id,
                'amigo_id' => $usuario->id,
                'friend' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }


    public function aceptarSolicitud(User $usuario)
    {
        DB::table('friendships')
            ->where('usuario_id', $usuario->id)
            ->where('amigo_id', $this->id)
            ->update(['friend' => true, 'updated_at' => now()]);
    }

    public function rechazarAmistad(User $usuario) //Sirve para borrar la amistad de la tabla este o no confirmada
    {
        DB::table('friendships')
            ->where(function ($query) use ($usuario) {
                $query->where('usuario_id', $this->id)
                    ->where('amigo_id', $usuario->id);
            })
            ->orWhere(function ($query) use ($usuario) {
                $query->where('usuario_id', $usuario->id)
                    ->where('amigo_id', $this->id);
            })
            ->delete();
    }
    public function esAmigoDe(User $usuario): bool
    {
        $id1 = min($this->id, $usuario->id);
        $id2 = max($this->id, $usuario->id);

        return DB::table('friendships')
            ->where('usuario_id', $id1)
            ->where('amigo_id', $id2)
            ->where('friend', true)
            ->exists();
    }

    //-------Funcines para el chat ------------------//
    public function chat($id)
    {
        return Chat::where(function ($query) {
            $query->where('participante_1', $this->id)
                ->orWhere('participante_2', $this->id);
        })->findOrFail($id);
    }

    public function eliminarChat($id)
    {
        Chat::where(function ($query) {
            $query->where('participante_1', $this->id)
                ->orWhere('participante_2', $this->id);
        })->where('id', $id)->delete();
    }

    public function crearChat(User $usuario)
    {
        Chat::create(['participante_1' => $this->id, 'participante_2' => $usuario->id]);
    }

    public function mensajesEnviados()
    {
        return $this->hasMany(Message::class, 'emisor_id');
    }

    public function mensajesRecibidos()
    {
        return $this->hasMany(Message::class, 'receptor_id');
    }

}
