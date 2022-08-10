<?php

namespace ViicSlen\TrackableTasks\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface TrackableType extends Arrayable
{
    public function getTrackableId(): mixed;

    public function getName(): string;

    public function getType(): string;

    public function getQueue(): ?string;

    public function getAttempts(): ?int;

    public function getTaskId();
}
