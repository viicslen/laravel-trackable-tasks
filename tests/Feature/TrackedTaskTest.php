<?php

use function Pest\Laravel\artisan;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Models\TrackedTask;

it('prunes old models', function () {
    TrackedTask::factory()
        ->sequence(
            ['created_at' => now()->subDays(100)],
            ['created_at' => now()->subDays(95)],
            ['created_at' => now()->subDays(93)],
            ['created_at' => now()->subDays(90)],
            ['created_at' => now()->subDays(29)],
            ['created_at' => now()->subDays(28)],
            ['created_at' => now()->subDays(27)],
        )
        ->count(7)
        ->create();

    artisan('model:prune', ['--model' => TrackedTask::class]);

    expect(TrackedTask::count())->toEqual(3);
});

it('doesn\'t prune models when config is set to null', function () {
    config()->set('trackable-tasks.prunable_after');

    TrackedTask::factory()
        ->sequence(
            ['created_at' => now()->subDays(100)],
            ['created_at' => now()->subDays(95)],
            ['created_at' => now()->subDays(93)],
            ['created_at' => now()->subDays(90)],
            ['created_at' => now()->subDays(29)],
            ['created_at' => now()->subDays(28)],
            ['created_at' => now()->subDays(27)],
        )
        ->count(7)
        ->create();

    artisan('model:prune', ['--model' => TrackedTask::class]);

    expect(TrackedTask::count())->toEqual(7);
});

it('updates the message', function () {
    $task = TrackedTask::factory()->create();

    $task->setMessage('test-message');
    $task->refresh();

    expect($task)->getMessage()->toEqual('test-message');
});

it('updates the output', function () {
    $task = TrackedTask::factory()->create();

    $task->setOutput(['key1' => 'hello', 'key2' => 'world']);
    $task->refresh();

    expect($task)->getOutput()->toEqual(['key1' => 'hello', 'key2' => 'world']);
});

it('updates the exceptions', function () {
    $task = TrackedTask::factory()->create();

    $task->setExceptions(['hello', 'world']);
    $task->refresh();

    expect($task)->getExceptions()->toEqual(['hello', 'world']);
});

it('updates the progress', function () {
    $task = TrackedTask::factory()->create();

    $task->setProgressMax(10);
    $task->setProgressNow(5);
    $task->refresh();

    expect($task)
        ->getProgressNow()->toEqual(5)
        ->getProgressMax()->toEqual(10)
        ->getProgressPercentage()->toEqual(50);
});

it('can add exception', function () {
    $task = TrackedTask::factory()->create();

    $task->addException('hello world');
    $task->refresh();

    expect($task)->exceptions->toEqual(['hello world']);
});

it('can be marked as started', function () {
    $task = TrackedTask::factory()->create();

    $task->markAsStarted();

    expect($task)
        ->status->toEqual(TrackableTask::STATUS_STARTED)
        ->is_executing->toBeTrue()
        ->started_at->not()->toBeNull();
});

it('can be marked as finished', function () {
    $task = TrackedTask::factory()->create();

    $task->markAsFinished('test-message');

    expect($task)
        ->status->toEqual(TrackableTask::STATUS_FINISHED)
        ->message->toEqual('test-message')
        ->is_finished->toBeTrue()
        ->hasFinished()->toBeTrue()
        ->finished_at->not()->toBeNull();
});

it('can be marked as failed', function () {
    $task = TrackedTask::factory()->create();

    $task->markAsFailed('test-exception');

    expect($task)
        ->status->toEqual(TrackableTask::STATUS_FAILED)
        ->message->toEqual('test-exception')
        ->is_failed->toBeTrue()
        ->finished_at->not()->toBeNull();
});

it('can calculate duration', function () {
    $task = TrackedTask::factory()->create();

    expect($task)->duration->ray()->toBeNull();

    $task->markAsStarted();

    sleep(2);

    expect($task)->duration->ray()->toEqual('2s');

    sleep(2);

    $task->markAsFinished();

    expect($task)->duration->ray()->toEqual('4s');
});
