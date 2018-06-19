<?php

namespace romanzipp\MakeFilePermissions\Listeners;

use Illuminate\Support\Facades\Storage;

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

        $type = str_replace('make:', '', $event->command);

        if (!$this->typeShouldBeHandled($type)) {
            return;
        }

        $path = $this->generatePath($type);

        if ($path == null) {
            return;
        }
    }

    /**
     * Determines wether the given type should be processed.
     * @param  string $type Class type
     * @return boolean
     */
    private function typeShouldBeHandled(string $type): bool
    {
        if ($type === 'auth') {
            return false;
        }

        $ignore = (array) config('make-file-permissions.ignore');

        if (in_array($type, $ignore)) {
            return false;
        }

        return true;
    }

    /**
     * Generate a given class path by type.
     * @param  string      $type Class type
     * @return string|null
     */
    private function generatePath(string $type)
    {
        switch ($type) {
            case 'migration':
                return $this->generateMigrationsPath($type);

            case 'test':
                return $this->generateTestsPath($type);

            default:
                return $this->generateGeneralPath($type);
        }
    }

    /**
     * Generate general class path.
     * @param  string      $type  Class type
     * @param  mixed       $event Incoming event
     * @return string|null
     */
    private function generateGeneralPath(string $type, $event)
    {
        $folder = array_get($this->paths, $type);

        if ($folder == null) {
            return null;
        }

        $file = $this->filename($event);
    }

    /**
     * Generate migration path.
     * @param  string      $type Class type
     * @return string|null
     */
    private function generateMigrationsPath(string $type)
    {
        $migrationsPath = database_path('migrations');

        $files = Storage::files($migrationsPath);
    }

    /**
     * Generate tests path.
     * @param  string      $type  Class type
     * @param  mixed       $event Incoming event
     * @return string|null
     */
    private function generateTestsPath(string $type, $event)
    {
        $testsPath = array_get($this->paths, 'feature');

        if ($event->input->getOption('unit')) {
            $testsPath = array_get($this->paths, 'unit');
        }

        return $this->filename($testsPath . $event);
    }

    /**
     * Get file system file name.
     * @param  mixed  $event Incoming event
     * @return string
     */
    private function filename($event): string
    {
        return $event->input->getArgument('name') . '.php';
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
