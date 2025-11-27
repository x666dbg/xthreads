<?php

namespace App\Models;

use App\Models\Concerns\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory, Likeable;

    protected $fillable = [
        'content',
        'user_id',
        'image',
        'parent_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Thread::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Thread::class, 'parent_id')->with('children');
    }

    public function repostedBy()
    {
        return $this->belongsToMany(User::class, 'reposts', 'thread_id', 'user_id')->withTimestamps();
    }

    public function mentions()
    {
        return $this->morphMany(Mention::class, 'mentionable');
    }
}