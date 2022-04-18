<?php

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithException;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithExceptionRecording;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithFail;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithMessage;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithOutput;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithoutTracking;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithTracking;

it('track batches', function () {
    $batch = TrackableTasks::batch([
        new TestJobWithoutTracking(),
        new TestJobWithoutTracking(),
    ], 'Test Batch')->dispatch();

    assertDatabaseHas('tracked_tasks', [
        'trackable_id' => $batch->id,
        'status' => TrackableTask::STATUS_FINISHED,
        'type' => TrackableTask::TYPE_BATCH,
    ]);
});

it('tracks finished tasks', function () {
    $job = new TestJobWithTracking();

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_QUEUED,
    ]);

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FINISHED,
    ]);
});

it('tracks failed status with exception', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithException();

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('tracks failed status with fail', function () {
    $job = new TestJobWithFail();

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('doesn\'t track jobs when should track is set to false', function () {
    $task = app(TrackableTask::class);
    $job = new TestJobWithoutTracking();

    expect($job->getTaskId())->toBeNull();
    expect($task::query()->count())->toEqual(0);

    dispatch($job);

    expect($task::query()->count())->toEqual(0);
});

it('records message', function () {
    $job = new TestJobWithMessage();

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'message' => 'hello world',
    ]);
});

it('records output', function () {
    $job = new TestJobWithOutput();

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'output' => "{\"key1\":\"hello\",\"key2\":\"world\"}",
    ]);
});

it('records exceptions', function () {
    $job = new TestJobWithExceptionRecording();

    dispatch($job);
    artisan('queue:work', ['--once' => 1]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'exceptions' => "[\"first-exception\",\"second-exception\",\"third-exception\"]",
    ]);
});
