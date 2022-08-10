# Laravel Trackable Tasks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/viicslen/laravel-trackable-tasks.svg?style=flat-square)](https://packagist.org/packages/viicslen/laravel-trackable-tasks)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/viicslen/laravel-trackable-tasks/run-tests?label=tests)](https://github.com/viicslen/laravel-trackable-tasks/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/viicslen/laravel-trackable-tasks/Check%20&%20fix%20styling?label=code%20style)](https://github.com/viicslen/laravel-trackable-tasks/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/viicslen/laravel-trackable-tasks.svg?style=flat-square)](https://packagist.org/packages/viicslen/laravel-trackable-tasks)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require viicslen/laravel-trackable-tasks
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="trackable-tasks-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="trackable-tasks-config"
```

This is the contents of the published config file:

```php
return [
    'queue_listener' => ViicSlen\TrackableTasks\QueueListeners\DefaultListener::class,
    'model' => ViicSlen\TrackableTasks\Models\TrackedTask::class,
    'prunable_after' => 90,
    'database' => [
        'connection' => null,
        'table' => 'tracked_task',
    ],
    'events' => [
        'retrieved' => ViicSlen\TrackableTasks\Events\TrackableTaskRetrieved::class,
        'creating' => ViicSlen\TrackableTasks\Events\TrackableTaskCreating::class,
        'created' => ViicSlen\TrackableTasks\Events\TrackableTaskCreated::class,
        'updating' => ViicSlen\TrackableTasks\Events\TrackableTaskUpdating::class,
        'updated' => ViicSlen\TrackableTasks\Events\TrackableTaskUpdated::class,
        'saving' => ViicSlen\TrackableTasks\Events\TrackableTaskSaving::class,
        'saved' => ViicSlen\TrackableTasks\Events\TrackableTaskSaved::class,
        'deleting' => ViicSlen\TrackableTasks\Events\TrackableTaskDeleting::class,
        'deleted' => ViicSlen\TrackableTasks\Events\TrackableTaskDeleted::class,
        'restoring' => ViicSlen\TrackableTasks\Events\TrackableTaskRestoring::class,
        'restored' => ViicSlen\TrackableTasks\Events\TrackableTaskRestored::class,
        'force_deleting' => ViicSlen\TrackableTasks\Events\TrackableTaskForceDeleted::class,
        'trashed' => ViicSlen\TrackableTasks\Events\TrackableTaskTrashed::class,
        'replicating' => ViicSlen\TrackableTasks\Events\TrackableTaskReplicating::class,
        'exception_added' => ViicSlen\TrackableTasks\Events\TrackableTaskExceptionAdded::class,
        'status_updated' => ViicSlen\TrackableTasks\Events\TrackableTaskStatusUpdated::class,
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-trackable-tasks-views"
```

## Usage

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

class TestJobWithTracking implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use Dispatchable;
    use TrackAutomatically;

    public function handle(): void {
        $this->taskSetProgressMax(200);

        $this->taskIncrementProgress();

        sleep(1);
        $this->taskIncrementProgress(10);

        sleep(1);
        $this->taskIncrementProgress(20);

        sleep(1);
        $this->taskIncrementProgress(30);

        sleep(1);
        $this->taskFinishProgress();
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Victor Rivero](https://github.com/viicslen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
