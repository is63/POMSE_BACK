<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{

    protected $fillable = [
      'texto', 'imagen', 'usuario_id', 'post_id'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class,'post_id');
    }

    public function user_listing(): BelongsTo
    {
        return $this->belongsTo(User_Listing::class, 'usuario_id');
    }
}
