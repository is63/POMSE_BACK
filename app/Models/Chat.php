<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'participante_1', 'participante_2'
    ];

    public function participante1(): BelongsTo
    {
        return $this->belongsTo(User_::class, 'participante_1');
    }

    public function participante2(): BelongsTo
    {
        return $this->belongsTo(User_::class, 'participante_2');
    }
}
