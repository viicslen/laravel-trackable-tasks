<?php

namespace ViicSlen\TrackableTasks\Listener;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Carbon;
use ViicSlen\TrackableTasks\Concerns\TrackableListener;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class JobProcessingListener
{
    use TrackableListener;

    public function handle(JobProcessing $event): void
    {
        if (!$this->isTrackableJob($event)) {
            return;
        }

        TrackableTasks::updateTask($event, [
            'status' => TrackableTask::STATUS_STARTED,
            'started_at' => Carbon::now(),
        ]);
    }
}
