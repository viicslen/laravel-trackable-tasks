<?php

namespace ViicSlen\TrackableTasks\Concerns;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
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
     */
    public function __construct(
        public TrackableTask|Model $trackableTask
    ) {
        $this->trackableTask = $trackableTask
            ->append('exceptions_count')
            ->makeHidden(['exceptions', 'output']);
    }
}
