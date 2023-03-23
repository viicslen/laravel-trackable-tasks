<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Batchable;
use RuntimeException;
use Throwable;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

trait Trackable
{
    public int $progressNow = 0;
    public int $progressMax = 100;

    public function failed(Throwable $exception): void
    {
        $this->taskSetMessage($exception->getMessage());
        $this->taskRecordException($exception->getMessage());
    }

    protected function taskSetProgressMax(int $value): bool
    {
        $this->progressMax = $value;

        return $this->taskUpdate(['progress_max' => $value]);
    }

    protected function taskSetProgressNow(int $value, int $every = 1): bool
    {
        $this->progressNow = $value;

        return ! ($value % $every === 0 || $value === $this->progressMax)
            || $this->taskUpdate(['progress_now' => $value]);
    }

    protected function taskIncrementProgress(int $offset = 1, int $every = 1): bool
    {
        $value = $this->progressNow + $offset;

        return $this->taskSetProgressNow($value, $every);
    }

    protected function taskFinishProgress(): bool
    {
        return $this->taskSetProgressNow($this->progressMax);
    }

    protected function taskSetMessage(string $message): bool
    {
        return $this->taskUpdate(['message' => $message]);
    }

    protected function taskClearMessage(): bool
    {
        return $this->taskUpdate(['message' => null]);
    }

    protected function taskSetExceptions(array $exceptions): bool
    {
        return $this->taskUpdate(['exceptions' => $exceptions]);
    }

    protected function taskSetOutput(array $output): bool
    {
        return $this->taskUpdate(['output' => $output]);
    }

    /**
     * @return array{0: $this, 1: \Illuminate\Support\Testing\BatchFake}
     */
    public function withFakeTrackableBatch(
        string $id = '',
        string $name = '',
        int $totalJobs = 0,
        int $pendingJobs = 0,
        int $failedJobs = 0,
        array $failedJobIds = [],
        array $options = [],
        ?CarbonImmutable $createdAt = null,
        ?CarbonImmutable $cancelledAt = null,
        ?CarbonImmutable $finishedAt = null
    ): array
    {
        $uses = class_uses_recursive(static::class);

        if (! isset($uses[Batchable::class])) {
            throw new RuntimeException(sprintf(
                'Job [%s] must use [%s] trait',
                static::class, Batchable::class
            ));
        }

        $taskName = $name ?? static::class;
        $task = TrackableTasks::createTask($taskName);

        $this->setTaskId($task->id);

        return $this->withFakeBatch(
            $id,
            $name,
            $totalJobs,
            $pendingJobs,
            $failedJobs,
            $failedJobIds,
            $options,
            $createdAt,
            $cancelledAt,
            $finishedAt
        );
    }
}
