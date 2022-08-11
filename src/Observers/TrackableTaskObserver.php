<?php

namespace ViicSlen\TrackableTasks\Observers;

use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Events\TrackableTaskCreated;
use ViicSlen\TrackableTasks\Events\TrackableTaskCreating;
use ViicSlen\TrackableTasks\Events\TrackableTaskDeleted;
use ViicSlen\TrackableTasks\Events\TrackableTaskDeleting;
use ViicSlen\TrackableTasks\Events\TrackableTaskExceptionAdded;
use ViicSlen\TrackableTasks\Events\TrackableTaskForceDeleted;
use ViicSlen\TrackableTasks\Events\TrackableTaskReplicating;
use ViicSlen\TrackableTasks\Events\TrackableTaskRestored;
use ViicSlen\TrackableTasks\Events\TrackableTaskRestoring;
use ViicSlen\TrackableTasks\Events\TrackableTaskRetrieved;
use ViicSlen\TrackableTasks\Events\TrackableTaskSaved;
use ViicSlen\TrackableTasks\Events\TrackableTaskSaving;
use ViicSlen\TrackableTasks\Events\TrackableTaskStatusUpdated;
use ViicSlen\TrackableTasks\Events\TrackableTaskTrashed;
use ViicSlen\TrackableTasks\Events\TrackableTaskUpdated;
use ViicSlen\TrackableTasks\Events\TrackableTaskUpdating;

class TrackableTaskObserver
{
    /**
     * Dispatch the events for the tracked task.
     *
     * @param  string  $key
     * @param  string  $default
     * @param  array  $args
     * @return void
     */
    protected function dispatchEvent(string $key, string $default, array $args): void
    {
        $event = config("trackable-tasks.events.$key", $default);

        if ($event) {
            event(new $event(...$args));
        }
    }

    /**
     * Handle the Task "retrieved" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function retrieved(TrackableTask $task): void
    {
        $this->dispatchEvent('retrieved', TrackableTaskRetrieved::class, [$task]);
    }

    /**
     * Handle the Task "creating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function creating(TrackableTask $task): void
    {
        $this->dispatchEvent('creating', TrackableTaskCreating::class, [$task]);
    }

    /**
     * Handle the Task "created" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function created(TrackableTask $task): void
    {
        $this->dispatchEvent('created', TrackableTaskCreated::class, [$task]);
    }

    /**
     * Handle the Task "updating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updating(TrackableTask $task): void
    {
        $this->dispatchEvent('updating', TrackableTaskUpdating::class, [$task]);
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updated(TrackableTask $task): void
    {
        $this->dispatchEvent('updated', TrackableTaskUpdated::class, [$task]);

        if ($task->wasChanged('status')) {
            $this->dispatchEvent('status_updated', TrackableTaskStatusUpdated::class, [$task]);
        }
    }

    /**
     * Handle the Task "saving" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function saving(TrackableTask $task): void
    {
        $this->dispatchEvent('saving', TrackableTaskSaving::class, [$task]);

        if ($task->isDirty('exceptions')) {
            $changes = array_intersect($task->getExceptions(), $task->getOriginal('exceptions'));

            foreach ($changes as $exception) {
                $this->dispatchEvent('exception_added', TrackableTaskExceptionAdded::class, [$task->getKey(), $exception]);
            }
        }
    }

    /**
     * Handle the Task "saved" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function saved(TrackableTask $task): void
    {
        $this->dispatchEvent('saved', TrackableTaskSaved::class, [$task]);
    }

    /**
     * Handle the Task "deleting" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleting(TrackableTask $task): void
    {
        $this->dispatchEvent('deleting', TrackableTaskDeleting::class, [$task]);
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleted(TrackableTask $task): void
    {
        $this->dispatchEvent('deleted', TrackableTaskDeleted::class, [$task]);
    }

    /**
     * Handle the Task "trashed" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function trashed(TrackableTask $task): void
    {
        $this->dispatchEvent('trashed', TrackableTaskTrashed::class, [$task]);
    }

    /**
     * Handle the Task "forceDeleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function forceDeleted(TrackableTask $task): void
    {
        $this->dispatchEvent('force_deleted', TrackableTaskForceDeleted::class, [$task]);
    }

    /**
     * Handle the Task "restoring" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restoring(TrackableTask $task): void
    {
        $this->dispatchEvent('restoring', TrackableTaskRestoring::class, [$task]);
    }

    /**
     * Handle the Task "restored" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restored(TrackableTask $task): void
    {
        $this->dispatchEvent('restored', TrackableTaskRestored::class, [$task]);
    }

    /**
     * Handle the Task "replicating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function replicating(TrackableTask $task): void
    {
        $this->dispatchEvent('replicating', TrackableTaskReplicating::class, [$task]);
    }
}
