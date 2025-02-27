<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Bus\Batchable;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use ViicSlen\TrackableTasks\Jobs\Middleware\TrackableBatch;

trait TrackAutomatically
{
    use Trackable;

    protected ?int $taskId = null;

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

    public function getTask(): ?TrackableTask
    {
        return TrackableTasks::getTask($this);
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

        $this->taskId = TrackableTasks::createTaskFrom($this, $data)->getKey();
    }

    protected function taskUpdate(array $data): bool
    {
        return TrackableTasks::updateTask($this, $data);
    }

    protected function taskRefresh(): void
    {
        if ($task = $this->getTask()) {
            $this->progressNow = $task->getProgressNow();
            $this->progressMax = $task->getProgressMax();
        }
    }

    protected function taskRecordException(mixed $exception): bool
    {
        return TrackableTasks::addTaskException($this, $exception);
    }
}
