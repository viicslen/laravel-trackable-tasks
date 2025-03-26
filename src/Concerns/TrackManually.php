<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

trait TrackManually
{
    use Trackable {
        taskClearMessage as baseTaskClearMessage;
    }

    protected TrackableTask $task;

    public function failed(Throwable $exception): void
    {
        $this->taskSetMessage($exception->getMessage(), TrackableTask::STATUS_FAILED);
        $this->taskRecordException($exception->getMessage());

        if (config('trackable-tasks.log_failures.enabled')) {
            Log::channel(config('trackable-tasks.log_failures.channel'))->error($exception->getMessage(), [
                'task_id' => $this->getTaskId(),
                'exception' => $exception,
            ]);
        }
    }

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

    protected function taskUpdate(array $data): bool
    {
        return $this->task->update($data);
    }

    protected function taskRefresh(): void
    {
        $this->task->refresh();

        $this->progressNow = $this->task->getProgressNow();
        $this->progressMax = $this->task->getProgressMax();
    }

    protected function taskStart(bool $refresh = false): void
    {
        if ($refresh) {
            $this->taskRefresh();
        }

        $this->taskUpdate([
            'status' => TrackableTask::STATUS_STARTED,
            'started_at' => $this->task->started_at ?? now(),
        ]);
    }

    protected function taskFinish(): void
    {
        $this->taskUpdate([
            'status' => TrackableTask::STATUS_FINISHED,
            'finished_at' => now(),
        ]);
    }

    protected function taskSetStatus(string $status): bool
    {
        if (! in_array($status, TrackableTask::STATUSES, true)) {
            throw new InvalidArgumentException('Invalid status provided. Allowed statuses: '.implode(', ', TrackableTask::STATUSES));
        }

        return $this->taskUpdate(['status' => $status]);
    }

    protected function taskSetMessage(string $message, ?string $status = null): bool
    {
        if (! $status) {
            return $this->taskUpdate(['message' => $message]);
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
