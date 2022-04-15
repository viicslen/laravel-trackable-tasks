<?php

namespace ViicSlen\TrackableTasks\Tests\Stub;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use ViicSlen\TrackableTasks\Concerns\Trackable;

class TestJobWithExceptionTries implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use Dispatchable;
    use Trackable;
    use Batchable;

    public int $tries = 3;

    public function handle(): void {
        throw new \Exception('test-exception');
    }
}