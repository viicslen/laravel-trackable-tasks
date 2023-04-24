<?php

namespace ViicSlen\TrackableTasks;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\QueueManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ViicSlen\TrackableTasks\Contracts\ListensToQueueEvents;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Models\TrackedException;
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
        $this->app->bind(ListensToQueueEvents::class, config('trackable-tasks.queue_listener'));
    }

    public function packageBooted(): void
    {
        /** @var \Illuminate\Queue\QueueManager $queueManger */
        $queueManger = app(QueueManager::class);

        /** @var \ViicSlen\TrackableTasks\Contracts\ListensToQueueEvents $eventManager */
        $eventManager = app(ListensToQueueEvents::class);

        // Add task observer
        app(TrackableTask::class)::observe(TrackableTaskObserver::class);
        TrackedException::observe(TrackableTaskObserver::class);

        // Add Event listeners
        $queueManger->before(fn (JobProcessing $event) => $eventManager->before($event));
        $queueManger->after(fn (JobProcessed $event) => $eventManager->after($event));
        $queueManger->failing(fn (JobFailed $event) => $eventManager->failing($event));
        $queueManger->exceptionOccurred(fn (JobExceptionOccurred $event) => $eventManager->exceptionOccurred($event));
    }
}
