<?php

namespace ViicSlen\TrackableTasks\Observers;

use ViicSlen\TrackableTasks\Enums\ExceptionSeverity;
use ViicSlen\TrackableTasks\Events\TrackableTaskExceptionAdded;
use ViicSlen\TrackableTasks\Models\TrackedException;

class TrackedExceptionObserver
{
    public function created(TrackedException $exception): void
    {
        $event = config("trackable-tasks.events.exception_added", TrackableTaskExceptionAdded::class);

        if ($event) {
            event(new $event($exception->tracked_task_id, $exception->message, match ($exception->severity) {
                ExceptionSeverity::WARNING => 'warning',
                ExceptionSeverity::INFO => 'info',
                default => 'error',
            }));
        }
    }
}
