<?php

namespace ViicSlen\TrackableTasks;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Listener\JobExceptionListener;
use ViicSlen\TrackableTasks\Listener\JobFailedListener;
use ViicSlen\TrackableTasks\Listener\JobProcessedListener;
use ViicSlen\TrackableTasks\Listener\JobProcessingListener;
use ViicSlen\TrackableTasks\Observers\TrackableTaskObserver;

class TrackableTasksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-trackable-tasks')
            ->hasMigration('create_trackable_tasks_table')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('trackable_tasks', fn () => new TrackableTasks());
        $this->app->bind(TrackableTask::class, config('trackable-tasks.model'));
    }

    public function packageBooted(): void
    {
        // Add task observer
        app(TrackableTask::class)::observe(TrackableTaskObserver::class);

        Event::listen(JobProcessed::class, JobProcessedListener::class);
        Event::listen(JobProcessing::class, JobProcessingListener::class);
        Event::listen(JobExceptionOccurred::class, JobExceptionListener::class);
        Event::listen(JobFailed::class, JobExceptionListener::class);
    }
}
