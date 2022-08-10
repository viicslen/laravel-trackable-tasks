<?php

namespace ViicSlen\TrackableTasks\Jobs\Middleware;

use Illuminate\Bus\Batchable;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;

class TrackableBatch
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return void
     */
    public function handle($job, callable $next): void
    {
        $uses = array_flip(class_uses_recursive($job));

        if (isset($uses[Batchable::class], $uses[TrackAutomatically::class]) && ($task = TrackableTasks::getTask($job))) {
            $job->progressNow = $task->getProgressNow();
            $job->progressMax = $task->getProgressMax();
        }

        $next($job);
    }
}
