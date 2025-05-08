<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';

    protected $fillable = [
        'participante_1', 'participante_2'
    ];

    public function participante1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participante_1');
    }

    public function participante2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participante_2');
    }

    public function mensajes()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
}
