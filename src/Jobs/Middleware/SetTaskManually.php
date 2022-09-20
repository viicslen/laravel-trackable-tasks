<?php

namespace ViicSlen\TrackableTasks\Jobs\Middleware;

use ViicSlen\TrackableTasks\Concerns\TrackManually;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

class SetTaskManually
{
    public function __construct(
        protected TrackableTask $task
    ) {}

    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return void
     */
    public function handle(mixed $job, callable $next): void
    {
        $uses = class_uses_recursive($job);
        if (! isset($uses[TrackManually::class]) && ! method_exists($job, 'setTask')) {
            throw new \RuntimeException('Job must use the TrackManually trait or implement the setTask method');
        }

        $job->setTask($this->task);

        $next($job);
    }
}
