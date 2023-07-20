<?php

namespace ViicSlen\TrackableTasks\Testing\Fakes;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackableListener;
use ViicSlen\TrackableTasks\Contracts\ListensToQueueEvents;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

class FakeListener implements ListensToQueueEvents
{
    use TrackableListener;

    protected array $events = [];

    public function before(JobProcessing $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        $this->events[] = [
            'job' => $event->job,
            'status' => TrackableTask::STATUS_STARTED,
            'started_at' => Carbon::now(),
        ];
    }

    public function after(JobProcessed $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        if ($event->job->hasFailed()) {
            return;
        }

        $this->events[] = [
            'job' => $event->job,
            'status' => TrackableTask::STATUS_FINISHED,
            'finished_at' => Carbon::now(),
        ];
    }

    public function failing(JobFailed|JobExceptionOccurred $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        if (is_null($event->job->maxTries()) || $event->job->attempts() >= $event->job->maxTries()) {
            $this->events[] = [
                'job' => $event->job,
                'status' => TrackableTask::STATUS_FINISHED,
                'finished_at' => Carbon::now(),
            ];

            return;
        }

        $this->events[] = [
            'job' => $event->job,
            'status' => TrackableTask::STATUS_RETRYING,
        ];
    }
}
