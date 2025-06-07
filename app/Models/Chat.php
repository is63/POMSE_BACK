<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';

    protected $fillable = [
        'name', 'is_group',
    ];

       public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function scopeWithLastMessage(Builder $query): void
{
    $query->addSelect([
        'last_message_at' => Message::select('created_at')
            ->whereColumn('chat_id', 'chats.id')
            ->latest()
            ->limit(1)
    ])->with(['messages' => function ($query) {
        $query->orderBy('created_at', 'desc')->limit(1); // Carga el último mensaje para mostrarlo en el sidebar
    }]);
}
}
