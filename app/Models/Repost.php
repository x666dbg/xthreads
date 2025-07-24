<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Repost extends Pivot
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reposts';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    // Define the relationships back to User and Thread
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}