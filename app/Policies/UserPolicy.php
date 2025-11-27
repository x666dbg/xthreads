<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can ban another user.
     */
    public function ban(User $user, User $model): bool
    {
        return $user->isModerator() && !$model->isModerator();
    }

    /**
     * Determine whether the user can unban another user.
     */
    public function unban(User $user, User $model): bool
    {
        return $user->isModerator();
    }
}