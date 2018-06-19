<?php

namespace romanzipp\MakeFilePermissions\Listeners;

class ApplyPermissionsOnMake
{
    /**
     * Listening paths
     * @var array
     */
    protected $paths = [
        'channel' => 'app/Broadcasting/',
        'command' => 'app/Console/Commands/',
        'controller' => 'app/Http/Controllers/',
        'event' => 'app/Events/',
        'exception' => 'app/Exceptions/',
        'factory' => 'database/factories/',
        'job' => 'app/Jobs/',
        'listener' => 'app/Listeners/',
        'mail' => 'app/Mail/',
        'middleware' => 'app/Http/Middleware/',
        'migration' => '',
        'model' => 'app/',
        'notification' => 'app/Notifications/',
        'policy' => 'app/Policies/',
        'provider' => 'app/Providers/',
        'request' => 'app/Http/Requests/',
        'resource' => 'app/Http/Resources/',
        'rule' => 'app/Rules/',
        'seeder' => 'database/seeds/',
        'feature' => 'tests/Feature/',
        'unit' => 'tests/Unit/',
        'widget' => 'app/Widgets/',
    ];

    /**
     * Handles the incoming event.
     * @param  mixed $event Incoming event
     * @return void
     */
    public function handle($event): void
    {
        if ($this->isProductionEnvironment()) {
            return;
        }

        if (!$this->validateMakeCommand($event)) {
            return;
        }

        $class = str_replace('make:', '', $event->command);
    }

    /**
     * Determines if given command is a "make" instruction.
     * @param  mixed $event Incoming event
     * @return boolean
     */
    private function validateMakeCommand($event): bool
    {
        return str_contains($event->command, 'make:');
    }

    /**
     * Determines if the app is in production environment.
     * @return boolean Is production environment
     */
    private function isProductionEnvironment(): bool
    {
        return config('app.env') == 'production';
    }
}
