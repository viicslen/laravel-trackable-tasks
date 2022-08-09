<?php

namespace ViicSlen\TrackableTasks\Concerns;

use ViicSlen\TrackableTasks\Contracts\TrackableTask;

trait ManuallyTrackable
{
    protected TrackableTask $task;
    public int $progressNow = 0;
    public int $progressMax = 100;

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
        return $this->task->addException($exception);
    }

    protected function taskSetOutput(array $output): bool
    {
        return $this->updateTask(['output' => $output]);
    }
}
