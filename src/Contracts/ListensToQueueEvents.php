<?php

namespace ViicSlen\TrackableTasks\Contracts;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

interface ListensToQueueEvents
{
    public function before(JobProcessing $event): void;

    public function after(JobProcessed $event): void;

    public function failing(JobFailed $event): void;

    public function exceptionOccurred(JobExceptionOccurred $event): void;
}
