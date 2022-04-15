<?php

use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithException;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithFailTries;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithExceptionTries;
use function Pest\Laravel\artisan;

it('updates tracked task', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithException;

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('updates retried task with exceptions', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithExceptionTries;

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_RETRYING,
    ]);
});

it('updates retried task with fails', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithFailTries;

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    $this->assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_RETRYING,
    ]);
});