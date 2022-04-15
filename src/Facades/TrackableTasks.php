<?php

namespace ViicSlen\TrackableTasks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask createTask($trackable, $data)
 * @method static \ViicSlen\TrackableTasks\Contracts\TrackableTask|null getTask($trackable)
 * @method static bool updateTask($trackable, $data)
 * @method static \Illuminate\Bus\PendingBatch batch(array|mixed $jobs, string $name = null)
 */
class TrackableTasks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'trackable_tasks';
    }
}