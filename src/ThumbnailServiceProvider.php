<?php

namespace Thumbnail;

use Illuminate\Support\ServiceProvider;

class ThumbnailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Thumbnail::class, function () {
            return new Thumbnail();
        });
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-thumbnail.php',
            'laravel-thumbnail'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-thumbnail.php' => config_path('laravel-thumbnail.php'),
        ], 'config');
    }
}
