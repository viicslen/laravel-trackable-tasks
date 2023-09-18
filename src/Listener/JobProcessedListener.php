<?php

namespace ViicSlen\TrackableTasks\Listener;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackableListener;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class JobProcessedListener
{
    use TrackableListener;

    public function handle(JobProcessed $event): void
    {
        if (!$this->isTrackableJob($event)) {
            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_FINISHED,
            'finished_at' => Carbon::now(),
        ]);
    }
}
