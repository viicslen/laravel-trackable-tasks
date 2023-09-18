<?php

use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades\TrackableTasks;
use Workbench\App\Jobs\TestJobWithException;
use Workbench\App\Jobs\TestJobWithExceptionRecording;
use Workbench\App\Jobs\TestJobWithFail;
use Workbench\App\Jobs\TestJobWithMessage;
use Workbench\App\Jobs\TestJobWithOutput;
use Workbench\App\Jobs\TestJobWithoutTracking;
use Workbench\App\Jobs\TestJobWithTracking;
use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;

it('track batches', function () {
    $batch = TrackableTasks::batch([
        new TestJobWithoutTracking(),
        new TestJobWithoutTracking(),
    ], 'Test Batch')->dispatch();

    artisan('queue:work', ['--stop-when-empty' => true]);

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
    artisan('queue:work', ['--stop-when-empty' => true]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FINISHED,
    ]);
});

it('tracks failed status with exception', function () {
    config()->set('queue.default', 'database');

    $job = new TestJobWithException();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'status' => TrackableTask::STATUS_FAILED,
    ]);
});

it('tracks failed status with fail', function () {
    $job = new TestJobWithFail();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

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
    artisan('queue:work', ['--stop-when-empty' => true]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'message' => 'hello world',
    ]);
});

it('records output', function () {
    $job = new TestJobWithOutput();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'output' => "{\"key1\":\"hello\",\"key2\":\"world\"}",
    ]);
});

it('records exceptions', function () {
    $job = new TestJobWithExceptionRecording();

    dispatch($job);
    artisan('queue:work', ['--stop-when-empty' => true]);

    assertDatabaseHas('tracked_tasks', [
        'id' => $job->getTaskId(),
        'exceptions' => "[\"first-exception\",\"second-exception\",\"third-exception\"]",
    ]);
});

it('uses a fake batch', function () {
    $name = 'Test batch';

    [$job, $batch] = (new TestJobWithTracking())->withFakeTrackableBatch(name: $name);

    expect($batch->name)->toEqual($name)
        ->and(TrackableTasks::getTask($job)->name)->toEqual($name);
});
