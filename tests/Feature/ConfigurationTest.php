<?php

use Illuminate\Support\Facades\Config;
use ViicSlen\TrackableTasks\Models\TrackedTask;
use ViicSlen\TrackableTasks\TrackableTasks;

/**
 * The manager reads the connection once in its constructor and keeps it in a
 * protected property, so read it back by reflection rather than adding a
 * getter to the public API purely for the sake of the test.
 */
function configuredConnection(): string
{
    $property = new ReflectionProperty(TrackableTasks::class, 'connection');

    return $property->getValue(new TrackableTasks());
}

it('uses the configured database connection', function () {
    Config::set('trackable-tasks.database.connection', 'custom_connection');

    expect(configuredConnection())->toBe('custom_connection');
});

it('falls back to the default connection when none is configured', function () {
    Config::set('trackable-tasks.database.connection', null);
    Config::set('database.default', 'testing');

    expect(configuredConnection())->toBe('testing');
});

it('uses the configured table', function () {
    Config::set('trackable-tasks.database.table', 'custom_tracked_tasks');

    expect((new TrackedTask())->getTable())->toBe('custom_tracked_tasks');
});

it('falls back to the default table when none is configured', function () {
    Config::set('trackable-tasks.database.table', null);

    expect((new TrackedTask())->getTable())->toBe('tracked_tasks');
});
