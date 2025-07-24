<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\Likeable;

class Thread extends Model
{
    use HasFactory, Likeable;

    // Kolom yang boleh diisi
    protected $fillable = [
        'content',
        'user_id',
        'image',
    ];

    // Definisikan relasi: Setiap Thread dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function repostedBy()
    {
        return $this->belongsToMany(User::class, 'reposts', 'thread_id', 'user_id');
    }

}