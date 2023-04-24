<?php

namespace ViicSlen\TrackableTasks\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use ViicSlen\TrackableTasks\TrackableTasksServiceProvider;

class TestCase extends Orchestra
{
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

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        $jobTable = include __DIR__.'/Stub/migrations/create_jobs_table.php';
        $jobTable->up();

        $jobBatchesTable = include __DIR__.'/Stub/migrations/create_job_batches_table.php';
        $jobBatchesTable->up();

        $migration = include __DIR__.'/../database/migrations/create_trackable_tasks_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_tracked_exceptions_table.php.stub';
        $migration->up();
    }
}
