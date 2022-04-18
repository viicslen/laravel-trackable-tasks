<?php

use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithoutTracking;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithTracking;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithFail;
use function Pest\Laravel\assertDatabaseHas;

it('can create task', function () {
    $job = new TestJobWithFail();
    $task = TrackableTasks::createTask($job, ['message' => 'Test message.']);

    expect($task)
        ->toBeInstanceOf(TrackableTask::class)
        ->toMatchArray(['message' => 'Test message.']);
});

it('can get task', function () {
    $job = new TestJobWithFail();

    $task = TrackableTasks::getTask($job);

    expect($task)->toBeInstanceOf(TrackableTask::class);
});

it('can update task', function () {
    $job = new TestJobWithFail();

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'trackable_id' => null,
    ]);

    TrackableTasks::updateTask($job, [
        'trackable_id' => 0,
        'status' => TrackableTask::STATUS_FAILED,
    ]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'trackable_id' => 0,
        'status' => TrackableTask::STATUS_FAILED,
    ]);

    TrackableTasks::updateTask($job, [
        'trackable_id' => 0,
        'status' => TrackableTask::STATUS_FINISHED,
    ]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'trackable_id' => 0,
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('can add exception', function () {
    $job = new TestJobWithTracking();

    TrackableTasks::addTaskException($job, 'first-exception');

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'exceptions' => "[\"first-exception\"]"
    ]);

    TrackableTasks::addTaskException($job, 'second-exception');

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'exceptions' => "[\"first-exception\",\"second-exception\"]"
    ]);
});

it('doesn\'t add exception when job is not trackable', function () {
    $job = new TestJobWithoutTracking();

    $updated = TrackableTasks::addTaskException($job, 'first-exception');

    expect($updated)->toBeFalse();
});
