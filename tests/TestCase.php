<?php

namespace ViicSlen\TrackableTasks\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use ViicSlen\TrackableTasks\TrackableTasksServiceProvider;

use function Orchestra\Testbench\workbench_path;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            static fn (string $modelName) => 'ViicSlen\\TrackableTasks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            TrackableTasksServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    public function getEnvironmentSetUp($app): void
    {
        $migration = include __DIR__.'/../database/migrations/create_trackable_tasks_table.php.stub';
        $migration->up();
    }
}
