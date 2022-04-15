<?php

namespace ViicSlen\TrackableTasks\Concerns;

use ViicSlen\TrackableTasks\Facades\TrackableTasks;

trait Trackable
{
    protected ?int $taskId = null;
    protected int $progressNow = 0;
    protected int $progressMax = 100;

    public function __construct()
    {
        $this->shouldTrack();
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

    protected function setProgressMax($value): bool
    {
        $this->progressMax = $value;

        return $this->updateTask(['progress_max' => $value]);
    }

    protected function setProgressNow($value, $every = 1): bool
    {
        $this->progressNow = $value;

        return ! ($value % $every === 0 || $value === $this->progressMax)
            || $this->updateTask(['progress_now' => $value]);
    }

    protected function incrementProgress($offset = 1, $every = 1): bool
    {
        $value = $this->progressNow + $offset;

        return $this->setProgressNow($value, $every);
    }

    protected function finishProgress(): bool
    {
        return $this->setProgressNow($this->progressMax);
    }
}
