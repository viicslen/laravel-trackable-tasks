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
use ViicSlen\TrackableTasks\Events\TrackableTaskTrashed;
use ViicSlen\TrackableTasks\Events\TrackableTaskUpdated;
use ViicSlen\TrackableTasks\Events\TrackableTaskUpdating;

class TrackableTaskObserver
{
    /**
     * Handle the User "retrieved" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function retrieved(TrackableTask $task): void
    {
        TrackableTaskRetrieved::dispatch($task);
    }

    /**
     * Handle the User "creating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function creating(TrackableTask $task): void
    {
        TrackableTaskCreating::dispatch($task);
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function created(TrackableTask $task): void
    {
        TrackableTaskCreated::dispatch($task);
    }

    /**
     * Handle the User "updating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updating(TrackableTask $task): void
    {
        TrackableTaskUpdating::dispatch($task);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updated(TrackableTask $task): void
    {
        TrackableTaskUpdated::dispatch($task);

        if ($task->wasChanged('status')) {
            TrackableTaskUpdated::dispatch($task);
        }
    }

    /**
     * Handle the User "saving" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function saving(TrackableTask $task): void
    {
        TrackableTaskSaving::dispatch($task);

        if ($task->isDirty('exceptions')) {
            $changes = array_intersect($task->getExceptions(), $task->getOriginal('exceptions'));

            foreach ($changes as $exception) {
                TrackableTaskExceptionAdded::dispatch($task->id, $exception);
            }
        }
    }

    /**
     * Handle the User "saved" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function saved(TrackableTask $task): void
    {
        TrackableTaskSaved::dispatch($task);
    }

    /**
     * Handle the User "deleting" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleting(TrackableTask $task): void
    {
        TrackableTaskDeleting::dispatch($task);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleted(TrackableTask $task): void
    {
        TrackableTaskDeleted::dispatch($task);
    }

    /**
     * Handle the User "trashed" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function trashed(TrackableTask $task): void
    {
        TrackableTaskTrashed::dispatch($task);
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function forceDeleted(TrackableTask $task): void
    {
        TrackableTaskForceDeleted::dispatch($task);
    }

    /**
     * Handle the User "restoring" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restoring(TrackableTask $task): void
    {
        TrackableTaskRestoring::dispatch($task);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restored(TrackableTask $task): void
    {
        TrackableTaskRestored::dispatch($task);
    }

    /**
     * Handle the User "replicating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function replicating(TrackableTask $task): void
    {
        TrackableTaskReplicating::dispatch($task);
    }
}
