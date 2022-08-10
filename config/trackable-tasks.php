<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Queue Listener
    |--------------------------------------------------------------------------
    |
    | The queue listener which updates the tracked task model when its status
    | changes.
    |
    */

    'queue_listener' => ViicSlen\TrackableTasks\QueueListeners\DefaultListener::class,

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Model
    |--------------------------------------------------------------------------
    |
    | The model that should be used to store the task status and progress. It
    | must implement the 'ViicSlen\TrackableTasks\Contracts\TrackableTask'
    | contract.
    |
    */

    'model' => ViicSlen\TrackableTasks\Models\TrackedTask::class,

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Prunable
    |--------------------------------------------------------------------------
    |
    | This config determines how old (in days) a tracked task has to be before
    | it gets pruned. If null, it will not delete any tracked tasks.
    |
    */

    'prunable_after' => 90,

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Database
    |--------------------------------------------------------------------------
    |
    | The table and connection where the tracked tasks will be stored. By
    | default, it will be stored in the 'tracked_tasks' table using the default
    | database connection.
    |
    | Note: Relevant only when using the default model provided by the package.
    |
    */

    'database' => [
        'connection' => null,

        'table' => 'tracked_tasks',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Model Events
    |--------------------------------------------------------------------------
    |
    | The event manager which updates the tracked task model when its status
    | changes.
    |
    */
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
