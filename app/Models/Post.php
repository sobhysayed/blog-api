<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }
}
