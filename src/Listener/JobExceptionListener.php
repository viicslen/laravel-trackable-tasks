<?php

namespace ViicSlen\TrackableTasks\Listener;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackableListener;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class JobExceptionListener
{
    use TrackableListener;

    public function handle(JobExceptionOccurred|JobFailed $event): void
    {
        if (!$this->isTrackableJob($event)) {
            return;
        }

        if ($event->job->maxTries() && $event->job->attempts() < $event->job->maxTries()) {
            TrackableTasks::updateTask($event, ['status' => TrackableTask::STATUS_RETRYING]);

            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_FAILED,
            'finished_at' => Carbon::now(),
        ]);
    }
}
