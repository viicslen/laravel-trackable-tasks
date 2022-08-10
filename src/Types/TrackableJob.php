<?php

namespace ViicSlen\TrackableTasks\Types;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

/**
 * @property ShouldQueue $trackable
 */
class TrackableJob extends TrackableType
{
    public const TYPE = 'job';

    public function __construct(mixed $trackable)
    {
        if ($this->isEvent($trackable)) {
            $trackable = $this->getEventJob($trackable);
        }

        parent::__construct($trackable);
    }

    protected function isEvent($trackable): bool
    {
        return $trackable instanceof JobProcessing
            || $trackable instanceof JobProcessed
            || $trackable instanceof JobFailed
            || $trackable instanceof JobExceptionOccurred;
    }

    protected function getEventJob(JobProcessing|JobProcessed|JobFailed|JobExceptionOccurred $event): ShouldQueue
    {
        $payload = $event->job->payload();

        return unserialize($payload['data']['command'], ['allowed_classes' => true]);
    }

    public function getTrackableId(): mixed
    {
        return $this->trackable->getJobId();
    }

    public function getName(): string
    {
        return method_exists($this->trackable, 'displayName') ? $this->trackable->displayName() : get_class($this->trackable);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getQueue(): ?string
    {
        return $this->trackable->queue ?? null;
    }

    public function getAttempts(): ?int
    {
        return method_exists($this->trackable, 'attempts') ? $this->trackable->attempts() : null;
    }

    public function getTaskId()
    {
        $uses = array_flip(class_uses_recursive($this->trackable));

        return isset($uses[TrackAutomatically::class]) || method_exists($this->trackable, 'getTaskId')
            ? $this->trackable->getTaskId()
            : null;
    }
}
