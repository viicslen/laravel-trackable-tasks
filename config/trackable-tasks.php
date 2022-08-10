<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Event Manager
    |--------------------------------------------------------------------------
    |
    | The event manager which updates the tracked task model when its status
    | changes.
    |
    */

    'event_manager' => ViicSlen\TrackableTasks\EventManagers\DefaultEventManager::class,

    /*
    |--------------------------------------------------------------------------
    | Tracked Task Types
    |--------------------------------------------------------------------------
    |
    | The types of tracked tasks that can be created. Used to get details about
    | the tracked task.
    |
    | It must implement the 'ViicSlen\TrackableTasks\Contracts\TrackableType'
    | contract.
    |
    */

    'types' => [
        ViicSlen\TrackableTasks\Types\TrackableJob::TYPE => ViicSlen\TrackableTasks\Types\TrackableJob::class,
        ViicSlen\TrackableTasks\Types\TrackableBatch::TYPE => ViicSlen\TrackableTasks\Types\TrackableBatch::class,
        ViicSlen\TrackableTasks\Types\TrackableModel::TYPE => ViicSlen\TrackableTasks\Types\TrackableModel::class,
    ],

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
];
