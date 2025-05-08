<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id', 'amigo_id'];


    // El usuario que envió la solicitud de amistad.
    public function sender()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // El usuario que recibió la solicitud de amistad.

    public function receiver()
    {
        return $this->belongsTo(User::class, 'amigo_id');
    }
}
