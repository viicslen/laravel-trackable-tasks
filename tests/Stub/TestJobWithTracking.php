<?php

namespace ViicSlen\TrackableTasks\Tests\Stub;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\Trackable;

class TestJobWithTracking implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use Dispatchable;
    use Trackable;
    use Batchable;

    public function handle(): void {
        $this->setProgressMax(200);

        $this->incrementProgress();

        sleep(1);
        $this->incrementProgress(10);

        sleep(1);
        $this->incrementProgress(20);

        sleep(1);
        $this->incrementProgress(30);

        sleep(1);
        $this->finishProgress();
    }
}