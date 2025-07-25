<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $guarded = []; // Izinkan semua kolom diisi

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
