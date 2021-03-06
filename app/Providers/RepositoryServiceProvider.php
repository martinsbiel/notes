<?php

namespace App\Providers;

use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\NoteRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\NoteRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
