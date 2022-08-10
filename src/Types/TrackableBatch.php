<?php

namespace ViicSlen\TrackableTasks\Types;


use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\PendingBatch;
use InvalidArgumentException;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

/**
 * @property PendingBatch|Batch $trackable
 */
class TrackableBatch extends TrackableType
{
    public const TYPE = 'batch';

    public function __construct(mixed $trackable)
    {
        $uses = array_flip(class_uses_recursive($trackable));

        /** @var PendingBatch|Batch $batch */
        $batch = match (true) {
            isset($uses[Batchable::class]) && $trackable->batching() => $trackable->batch(),
            $trackable instanceof PendingBatch || $trackable instanceof Batch => $trackable,
            default => throw new InvalidArgumentException('Provided trackable must be an instance of Illuminate\Bus\Batch, Illuminate\Bus\PendingBatch or a job that uses the Illuminate\Bus\Batchable trait.'),
        };

        parent::__construct($batch);
    }

    public function getTrackableId(): mixed
    {
        return $this->trackable->id ?? null;
    }

    public function getName(): string
    {
        return $this->trackable->name ?? 'Batch';
    }

    public function getQueue(): ?string
    {
        return $this->options['queue'] ?? null;
    }

    public function getTaskId()
    {
        $this->trackable->jobs
            ->where(function ($job) {
                $uses = array_flip(class_uses_recursive($job));

                return isset($uses[TrackAutomatically::class]) && $job->getJobId() !== null;
            })
            ->first()
            ->getJobId();
    }
}
