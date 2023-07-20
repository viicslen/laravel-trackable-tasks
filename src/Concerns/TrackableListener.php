<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

trait TrackableListener
{
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

    public function exceptionOccurred(JobExceptionOccurred $event): void
    {
        $this->failing($event);
    }
}
