<?php

namespace ViicSlen\TrackableTasks\Tests\Stub;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;

class TestJobWithFail implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use Dispatchable;
    use TrackAutomatically;
    use Batchable;

    public function handle(): void
    {
        $this->fail(new \Exception('test-exception'));
    }
}
