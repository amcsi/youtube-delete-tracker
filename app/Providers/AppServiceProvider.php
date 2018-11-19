<?php

namespace App\Providers;

use App\ThirdParty\Youtube\YoutubeFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app->singleton(\Google_Service_YouTube::class, function () use ($app) {
            return (new YoutubeFactory($app->make(\Google_Client::class)))->__invoke();
        });
    }
}
