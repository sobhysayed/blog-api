<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
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

    public function posts(): Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function comments(): Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function likes(): Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function followers(): Relations\HasMany
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    public function followings(): Relations\HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }
}
