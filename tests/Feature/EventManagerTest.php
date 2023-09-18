<?php

use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use Workbench\App\Jobs\TestJobWithException;
use Workbench\App\Jobs\TestJobWithExceptionTries;
use Workbench\App\Jobs\TestJobWithFailTries;
use function Pest\Laravel\artisan;

it('updates tracked task', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithException();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('updates retried task with exceptions', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithExceptionTries();

    dispatch($job);
    artisan('queue:work', ['--once' => true]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_RETRYING,
    ]);
});

it('updates retried task with fails', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithFailTries();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_RETRYING,
    ]);
});
