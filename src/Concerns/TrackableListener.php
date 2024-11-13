<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

trait TrackableListener
{
    /**
     * Determine whether the job should be handled.
     *
     * @param JobProcessing|JobProcessed|JobFailed|JobExceptionOccurred $event
     */
    public function isTrackableJob(mixed $event): bool
    {
        $payload = $event->job->payload();

        $job = isset($payload['data']['commandName'])
            ? $payload['data']['commandName']
            : unserialize($payload['data']['command'], ['allowed_classes' => true]);

        $uses = class_uses_recursive($job);

        return isset($uses[TrackAutomatically::class]);
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  JobProcessing|JobProcessed|JobFailed|JobExceptionOccurred  $event
     */
    public function shouldQueue(mixed $event): bool
    {
        return $this->isTrackableJob($event);
    }

    /**
     * Get the name of the listener's queue connection.
     */
    public function viaConnection(): string
    {
        return config('trackable-tasks.listeners.connection', config('queue.default')) ?? 'sync';
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return config('trackable-tasks.listeners.queue', 'default');
    }
}
