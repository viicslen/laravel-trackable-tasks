<?php

namespace ViicSlen\TrackableTasks\Facades;

use Illuminate\Support\Facades\Facade;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Testing\Fakes\TrackableTasksFake;
use ViicSlen\TrackableTasks\Testing\Fakes\TrackedTaskFake;

/**
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask|\Illuminate\Database\Eloquent\Model model()
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder query()
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask createTask(array|string $data)
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask createTaskFrom($trackable, array $data)
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask|null getTask($trackable)
 * @method static bool updateTask($trackable, array $data)
 * @method static bool addTaskException($trackable, mixed $exception)
 * @method static \Illuminate\Bus\PendingBatch batch(array|mixed $jobs, string $name = null)
 */
class TrackableTasks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'trackable_tasks';
    }

    public static function fake(): TrackableTasksFake
    {
        self::$app->bind(TrackableTask::class, TrackedTaskFake::class);

        static::swap($fake = new TrackableTasksFake);

        return $fake;
    }
}
