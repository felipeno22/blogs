<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        //Gate é uma classe responsavel por restringir acessos
        //atraves de uma condição
            Gate::define('autoriza_admin', function(User $user){
                return $user->role == 'admin';

            });

            Gate::define('autoriza_admin_author', function(User $user){
             return $user->role === 'admin' || $user->role === 'author';

            });

            Gate::define('autoriza_todos_users', function(User $user){
                return in_array($user->role, ['admin', 'author', 'user']);

            });




    }
}
