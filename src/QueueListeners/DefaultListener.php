<?php

namespace ViicSlen\TrackableTasks\QueueListeners;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackableListener;
use ViicSlen\TrackableTasks\Contracts\ListensToQueueEvents;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class DefaultListener implements ListensToQueueEvents
{
    use TrackableListener;

    public function before(JobProcessing $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_STARTED,
            'started_at' => Carbon::now(),
        ]);
    }

    public function after(JobProcessed $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        if ($event->job->hasFailed()) {
            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_FINISHED,
            'finished_at' => Carbon::now(),
        ]);
    }

    public function failing(JobFailed|JobExceptionOccurred $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        if (is_null(! $event->job->maxTries()) && $event->job->attempts() < $event->job->maxTries()) {
            TrackableTasks::updateTask($event, ['status' => TrackableTask::STATUS_RETRYING]);

            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_FAILED,
            'finished_at' => Carbon::now(),
        ]);
    }
}
