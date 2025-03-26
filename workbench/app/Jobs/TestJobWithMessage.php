<?php

namespace Workbench\App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

class TestJobWithMessage implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use TrackAutomatically;

    public function handle(): void
    {
        $this->taskSetMessage('hello world');
    }
}
