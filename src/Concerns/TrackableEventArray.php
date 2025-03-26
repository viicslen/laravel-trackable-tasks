<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

trait TrackableEventArray
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $trackableTask;

    /**
     * Create a new event instance.
     */
    public function __construct(
        TrackableTask|Model $trackableTask
    ) {
        $this->trackableTask = $trackableTask->makeHidden([
            'exceptions',
            'output',
        ])->toArray();
    }
}
