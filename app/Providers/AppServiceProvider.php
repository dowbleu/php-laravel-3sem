<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;

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
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();

        Gate::before(function(User $user){
            if ($user->role == "moderator")
                return true;
        });

        Gate::define('comment', function(User $user, Comment $comment){
            if ($user->role == "moderator") {
                return Response::allow();
            }

            return ($user->id == $comment->users_id) 
                ? Response::allow()
                : Response::deny('You can only edit or delete your own comments');
        });
    }
}