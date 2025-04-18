<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'chat_id',
        'imagen',
        'texto',
    ];



    protected static function boot()
    {
        parent::boot();

        //Comprueba que el mensaje no este vacío
        static::creating(function ($message) {
            if (empty($message->texto) && empty($message->imagen)) {
                throw new \Exception('El mensaje debe tener al menos texto o una imagen.');
            }
        });

        //Comprueba que el emisor y receptor no sean el mismo usuario
        static::creating(function ($message) {
            if ($message->emisor_id === $message->receptor_id) {
                throw new \Exception('No se puede enviar un mensaje a uno mismo.');
            }
        });
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }


}
