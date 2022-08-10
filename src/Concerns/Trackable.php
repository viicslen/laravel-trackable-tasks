<?php

namespace ViicSlen\TrackableTasks\Concerns;

trait Trackable
{
    public int $progressNow = 0;
    public int $progressMax = 100;

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

    protected function taskSetOutput(array $output): bool
    {
        return $this->updateTask(['output' => $output]);
    }
}
