<?php

use function Pest\Laravel\assertDatabaseHas;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use ViicSlen\TrackableTasks\Tests\Stub\TestJobWithFail;

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
