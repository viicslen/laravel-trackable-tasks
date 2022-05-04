<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Bus\Batchable;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use ViicSlen\TrackableTasks\Jobs\Middleware\TrackableBatch;

trait Trackable
{
    protected ?int $taskId = null;
    public int $progressNow = 0;
    public int $progressMax = 100;

    public function __construct()
    {
        $this->shouldTrack();
    }

    public function middleware(): array
    {
        $uses = array_flip(class_uses_recursive($this));

        return isset($uses[Batchable::class]) ? [new TrackableBatch()] : [];
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }

    protected function shouldTrack(array $data = []): void
    {
        if (isset($this->shouldTrack) && $this->shouldTrack === false) {
            return;
        }

        $this->taskId = TrackableTasks::createTask($this, $data)->getKey();
    }

    protected function updateTask(array $data): bool
    {
        return TrackableTasks::updateTask($this, $data);
    }

    protected function taskSetProgressMax(int $value): bool
    {
        $this->progressMax = $value;

        return $this->updateTask(['progress_max' => $value]);
    }

    protected function taskSetProgressNow(int $value, int $every = 1): bool
    {
        $this->progressNow = $value;

        return ! ($value % $every === 0 || $value === $this->progressMax)
            || $this->updateTask(['progress_now' => $value]);
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
        return $this->updateTask(['message' => $message]);
    }

    protected function taskSetExceptions(array $exceptions): bool
    {
        return $this->updateTask(['exceptions' => $exceptions]);
    }

    protected function taskRecordException(mixed $exception): bool
    {
        return TrackableTasks::addTaskException($this, $exception);
    }

    protected function taskSetOutput(array $output): bool
    {
        return $this->updateTask(['output' => $output]);
    }
}
