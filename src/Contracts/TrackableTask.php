<?php

namespace ViicSlen\TrackableTasks\Contracts;

/**
 * TrackableTask class
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface TrackableTask
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_STARTED = 'started';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_FAILED = 'failed';
    public const STATUS_RETRYING = 'retrying';
    public const STATUSES = [
        self::STATUS_QUEUED,
        self::STATUS_STARTED,
        self::STATUS_FINISHED,
        self::STATUS_FAILED,
        self::STATUS_RETRYING,
    ];

    public const TYPE_JOB = 'job';
    public const TYPE_BATCH = 'batch';
    public const TYPES = [
        self::TYPE_JOB,
        self::TYPE_BATCH,
    ];

    public function setProgressNow(int $value): bool;

    public function getProgressNow(): int;

    public function setProgressMax(int $value): bool;

    public function getProgressMax(): int;

    public function getProgressPercentage(): int;

    public function setMessage(string $message): bool;

    public function getMessage(): string;

    public function setExceptions(array $exceptions): bool;

    public function getExceptions(): array;

    public function addException(mixed $exception): bool;

    public function setOutput(array $output): bool;

    public function getOutput(): array;

    public function markAsStarted(): bool;

    public function markAsFinished(string $message = null): bool;

    public function markAsFailed(string $exception = null): bool;

    public function hasStarted(): bool;

    public function hasFinished(): bool;

    public function hasFailed(): bool;
}
