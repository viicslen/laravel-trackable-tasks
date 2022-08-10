<?php

namespace ViicSlen\TrackableTasks\Types;


class TrackableModel extends TrackableType
{
    public const TYPE = 'model';

    public function __construct(
        public readonly mixed $trackable,
    ) {}

    public function getKey(): mixed
    {
        return $this->trackable->getKey();
    }

    public function getName(): string
    {
        return $this->trackable->name ?? $this->trackable->getMorphClass() ?? get_class($this->trackable);
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
        return $this->trackable->attempts ?? null;
    }

    public function getTrackableId(): mixed
    {
        // TODO: Implement getTrackableId() method.
    }

    public function getTaskId()
    {
        // TODO: Implement getTaskId() method.
    }
}
