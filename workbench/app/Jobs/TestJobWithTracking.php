<?php

namespace Workbench\App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

class TestJobWithTracking implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use TrackAutomatically;

    public function handle(): void
    {
        $this->taskSetProgressMax(200);

        $this->taskIncrementProgress();

        sleep(1);
        $this->taskIncrementProgress(10);

        sleep(1);
        $this->taskIncrementProgress(20);

        sleep(1);
        $this->taskIncrementProgress(30);

        sleep(1);
        $this->taskFinishProgress();
    }
}
