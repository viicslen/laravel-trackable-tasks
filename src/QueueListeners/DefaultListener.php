<?php

namespace ViicSlen\TrackableTasks\QueueListeners;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;
use ViicSlen\TrackableTasks\Contracts\ListensToQueueEvents;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class DefaultListener implements ListensToQueueEvents
{
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

    public function failing(JobFailed $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        $status = (is_null($event->job->maxTries()) || $event->job->attempts() >= $event->job->maxTries())
            ? TrackableTask::STATUS_FAILED
            : TrackableTask::STATUS_RETRYING;

        TrackableTasks::updateTask($event, [
            'status' => $status,
            'finished_at' => Carbon::now(),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function exceptionOccurred(JobExceptionOccurred $event): void
    {
        if (! $this->canBeTracked($event)) {
            return;
        }

        $status = (is_null($event->job->maxTries()) || $event->job->attempts() >= $event->job->maxTries())
            ? TrackableTask::STATUS_FAILED
            : TrackableTask::STATUS_RETRYING;

        TrackableTasks::updateTask($event, [
            'status' => $status,
            'finished_at' => Carbon::now(),
        ]);
    }

    protected function canBeTracked(JobProcessing|JobProcessed|JobFailed|JobExceptionOccurred $event): bool
    {
        if ($event->connectionName === 'sync' || $event->job->getQueue() === 'sync') {
            return false;
        }

        $payload = $event->job->payload();
        $job = unserialize($payload['data']['command'], ['allowed_classes' => true]);
        $uses = class_uses_recursive($job);

        return isset($uses[TrackAutomatically::class]);
    }
}
