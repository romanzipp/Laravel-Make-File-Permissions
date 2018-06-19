<?php

namespace romanzipp\MakeFilePermissions\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class MakeFilePermissionsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../make-file-permissions.php' => config_path('make-file-permissions.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../make-file-permissions.php', 'make-file-permissions'
        );

        if (config('make-file-permissions.enabled')) {
            Event::listen(
                \Illuminate\Console\Events\CommandFinished::class,
                \romanzipp\MakeFilePermissions\Listeners\ApplyPermissionsOnMake::class
            );
        }
    }
}
