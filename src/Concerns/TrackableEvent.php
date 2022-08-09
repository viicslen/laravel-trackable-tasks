<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

trait TrackableEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \ViicSlen\TrackableTasks\Contracts\TrackableTask  $trackableTask
     */
    public function __construct(
        public TrackableTask $trackableTask
    ) {
        $this->trackableTask = $trackableTask->makeHidden(['exceptions']);
    }
}
