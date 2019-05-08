<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Request\RequestRepositoryInterface;
use App\Repositories\Request\RequestRepository;

class RequestRepoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);
    }
}
