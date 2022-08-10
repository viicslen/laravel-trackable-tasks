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
     * Handle the User "retrieved" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function retrieved(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.retrieved', TrackableTaskRetrieved::class))::dispatch($task);
    }

    /**
     * Handle the User "creating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function creating(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.creating', TrackableTaskCreating::class))::dispatch($task);
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function created(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.created', TrackableTaskCreated::class))::dispatch($task);
    }

    /**
     * Handle the User "updating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updating(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.updating', TrackableTaskUpdating::class))::dispatch($task);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function updated(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.updated', TrackableTaskUpdated::class))::dispatch($task);

        if ($task->wasChanged('status')) {
            app(config('trackable-tasks.events.status_updated', TrackableTaskStatusUpdated::class))::dispatch($task);
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
        app(config('trackable-tasks.events.saving', TrackableTaskSaving::class))::dispatch($task);

        if ($task->isDirty('exceptions')) {
            $changes = array_intersect($task->getExceptions(), $task->getOriginal('exceptions'));

            foreach ($changes as $exception) {
                app(config('trackable-tasks.events.exception_added', TrackableTaskExceptionAdded::class))::dispatch($task, $exception);
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
        app(config('trackable-tasks.events.saved', TrackableTaskSaved::class))::dispatch($task);
    }

    /**
     * Handle the User "deleting" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleting(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.deleting', TrackableTaskDeleting::class))::dispatch($task);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function deleted(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.deleted', TrackableTaskDeleted::class))::dispatch($task);
    }

    /**
     * Handle the User "trashed" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function trashed(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.trashed', TrackableTaskTrashed::class))::dispatch($task);
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function forceDeleted(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.force_deleted', TrackableTaskForceDeleted::class))::dispatch($task);
    }

    /**
     * Handle the User "restoring" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restoring(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.restoring', TrackableTaskRestoring::class))::dispatch($task);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function restored(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.restored', TrackableTaskRestored::class))::dispatch($task);
    }

    /**
     * Handle the User "replicating" event.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $task
     * @return void
     */
    public function replicating(TrackableTask $task): void
    {
        app(config('trackable-tasks.events.replicating', TrackableTaskReplicating::class))::dispatch($task);
    }
}
