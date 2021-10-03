<?php

namespace romanzipp\MakeFilePermissions\Listeners;

use Illuminate\Console\Events\CommandFinished;

class ApplyPermissionsOnMake
{
    /**
     * Listening paths.
     *
     * @var array<string, string>
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
     *
     * @param mixed $event Incoming event
     *
     * @return void
     */
    public function handle($event): void
    {
        if ($this->isProductionEnvironment()) {
            return;
        }

        if ( ! $this->validateMakeCommand($event)) {
            return;
        }

        $type = str_replace('make:', '', $event->command);

        if ( ! $this->typeShouldBeHandled($type)) {
            return;
        }

        $path = $this->generatePath($type, $event);

        if (null == $path) {
            return;
        }

        $this->executeCommand($path);
    }

    /**
     * Execute the chmod command.
     *
     * @param string $path File path
     *
     * @return void
     */
    public function executeCommand(string $path): void
    {
        $permission = config('make-file-permissions.permission');

        $command = escapeshellcmd('chmod ' . $permission . ' ' . $path);

        exec($command);
    }

    /**
     * Determines wether the given type should be processed.
     *
     * @param string $type Class type
     *
     * @return bool
     */
    private function typeShouldBeHandled(string $type): bool
    {
        if ('auth' === $type) {
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
     *
     * @param string $type Class type
     * @param \Illuminate\Console\Events\CommandFinished $event Incoming event
     *
     * @return string|null
     */
    private function generatePath(string $type, CommandFinished $event)
    {
        switch ($type) {
            case 'migration':
                return $this->generateMigrationsPath();

            case 'test':
                return $this->generateTestsPath($event);

            default:
                return $this->generateGeneralPath($type, $event);
        }
    }

    /**
     * Generate general class path.
     *
     * @param string $type Class type
     * @param \Illuminate\Console\Events\CommandFinished $event Incoming event
     *
     * @return string|null
     */
    private function generateGeneralPath(string $type, CommandFinished $event)
    {
        $folder = array_get($this->paths, $type);

        if (null == $folder) {
            return null;
        }

        $file = $this->filename($event);

        return $folder . $file;
    }

    /**
     * Generate migration path.
     *
     * @return string|null
     */
    private function generateMigrationsPath()
    {
        $migrationsPath = database_path('migrations') . '/*';

        $files = glob($migrationsPath);

        $last = array_last($files);

        return $last;
    }

    /**
     * Generate tests path.
     *
     * @param \Illuminate\Console\Events\CommandFinished $event Incoming event
     *
     * @return string
     */
    private function generateTestsPath(CommandFinished $event)
    {
        $testsPath = array_get($this->paths, 'feature');

        if ($event->input->getOption('unit')) {
            $testsPath = array_get($this->paths, 'unit');
        }

        return $this->filename($testsPath . $event);
    }

    /**
     * Get file system file name.
     *
     * @param \Illuminate\Console\Events\CommandFinished $event Incoming event
     *
     * @return string
     */
    private function filename(CommandFinished $event): string
    {
        return $event->input->getArgument('name') . '.php';
    }

    /**
     * Determines if given command is a "make" instruction.
     *
     * @param \Illuminate\Console\Events\CommandFinished $event Incoming event
     *
     * @return bool
     */
    private function validateMakeCommand(CommandFinished $event): bool
    {
        return str_contains($event->command, 'make:');
    }

    /**
     * Determines if the app is in production environment.
     *
     * @return bool Is production environment
     */
    private function isProductionEnvironment(): bool
    {
        return 'production' === config('app.env');
    }
}
