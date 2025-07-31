<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Thread;

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
        'username',
        'email',
        'password',
        'photo',
        'bio',
        'cover_photo',
        'location',
        'role',
        'is_banned',
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

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }


    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'following_user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_user_id', 'user_id');
    }
    public function reposts()
    {
        return $this->belongsToMany(Thread::class, 'reposts', 'user_id', 'thread_id')->withTimestamps();
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }
}
