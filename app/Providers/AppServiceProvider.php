<?php

namespace App\Providers;

use App\Models\Thread;
use App\Policies\ThreadPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Thread::class, ThreadPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
