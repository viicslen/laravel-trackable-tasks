<?php

namespace ViicSlen\TrackableTasks\Concerns;

use InvalidArgumentException;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

trait TrackManually
{
    use Trackable { taskClearMessage as baseTaskClearMessage; }

    protected TrackableTask $task;

    public function getTask(): TrackableTask
    {
        return $this->task;
    }

    public function setTask(TrackableTask $task): void
    {
        $this->task = $task;
    }

    public function getTaskId(): ?int
    {
        return $this->task->getKey();
    }

    public function setTaskId(int $taskId): void
    {
        $this->task = app(TrackableTask::class)->findOrFail($taskId);
    }

    protected function updateTask(array $data): bool
    {
        return $this->task->update($data);
    }

    protected function refreshTask(): void
    {
        $this->task->refresh();

        $this->progressNow = $this->task->getProgressNow();
        $this->progressMax = $this->task->getProgressMax();
    }

    protected function taskSetStatus(string $status): bool
    {
        if (! in_array($status, TrackableTask::STATUSES, true)) {
            throw new InvalidArgumentException('Invalid status provided. Allowed statuses: ' . implode(', ', TrackableTask::STATUSES));
        }

        return $this->updateTask(['status' => $status]);
    }

    protected function taskSetMessage(string $message, ?string $status = null): bool
    {
        if (! $status) {
            return $this->updateTask(['message' => $message]);
        }

        $this->task->setAttribute('message', $message);

        return $this->taskSetStatus($status);
    }

    protected function taskClearMessage(?string $status = null): bool
    {
        if (! $status) {
            return $this->baseTaskClearMessage();
        }

        $this->task->setAttribute('message', null);

        return $this->taskSetStatus($status);
    }

    protected function taskRecordException(mixed $exception): bool
    {
        return $this->task->addException($exception);
    }
}
