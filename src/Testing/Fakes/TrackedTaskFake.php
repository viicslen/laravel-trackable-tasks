<?php

namespace ViicSlen\TrackableTasks\Testing\Fakes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

/**
 * TrackedTask class
 *
 * @property int $id
 * @property string $trackable_id
 * @property string $type
 * @property string $name
 * @property string $queue
 * @property string $status
 * @property string $message
 * @property int $progress_now
 * @property int $progress_max
 * @property int $attempts
 * @property array $exceptions
 * @property array $output
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property-read int $progress_percentage
 * @property-read string $duration
 * @property-read bool $is_queued
 * @property-read bool $is_executing
 * @property-read bool $is_finished
 * @property-read bool $is_failed
 * @property-read bool $is_ended
 *
 * @mixin \ViicSlen\TrackableTasks\Contracts\TrackableTask
 */
class TrackedTaskFake extends Fluent implements TrackableTask
{
    use HasAttributes;

    protected $casts = [
        'output' => 'array',
        'exceptions' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected $attributes = [
        'type' => TrackableTask::TYPE_JOB,
        'status' => TrackableTask::STATUS_QUEUED,
        'progress_now' => 0,
        'progress_max' => 0,
        'attempts' => 0,
        'exceptions' => '[]',
    ];

    public static function create(array $attributes = []): static
    {
        return new static(array_merge($attributes, ['id' => Str::orderedUuid()]));
    }

    public static function make(array $attributes = []): static
    {
        return static::create($attributes);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return true;
    }

    public function setProgressNow(int $value): bool
    {
        return $this->update(['progress_now' => $value]);
    }

    public function getProgressNow(): int
    {
        return $this->progress_now;
    }

    public function setProgressMax(int $value): bool
    {
        return $this->update(['progress_max' => $value]);
    }

    public function getProgressMax(): int
    {
        return $this->progress_max;
    }

    public function getProgressPercentage(): int
    {
        return $this->progress_percentage;
    }

    public function setMessage(string $message): bool
    {
        return $this->update([
            'message' => $message,
        ]);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setExceptions(array $exceptions): bool
    {
        return $this->update([
            'exceptions' => $exceptions,
        ]);
    }

    public function getExceptions(): ?array
    {
        return $this->exceptions ?? [];
    }

    public function addException(mixed $exception): bool
    {
        return $this->setExceptions(array_merge($this->exceptions ?? [], [$exception]));
    }

    public function setOutput(array $output): bool
    {
        return $this->update([
            'output' => $output,
        ]);
    }

    public function getOutput(): array
    {
        return $this->output;
    }

    public function markAsStarted(): bool
    {
        return $this->update([
            'status' => static::STATUS_STARTED,
            'started_at' => now(),
        ]);
    }

    public function markAsFinished(string $message = null): bool
    {
        if ($message) {
            $this->setMessage($message);
        }

        return $this->update([
            'status' => static::STATUS_FINISHED,
            'finished_at' => now(),
        ]);
    }

    public function markAsFailed(string $exception = null): bool
    {
        if ($exception) {
            $this->setMessage($exception);
        }

        return $this->update([
            'status' => static::STATUS_FAILED,
            'finished_at' => now(),
        ]);
    }

    public function hasStarted(): bool
    {
        return ! is_null($this->started_at);
    }

    public function hasFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }

    public function isStopped(): bool
    {
        return $this->status === self::STATUS_STOPPED;
    }

    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::get(fn (): int => $this->progress_max !== 0 ? round(100 * $this->progress_now / $this->progress_max) : 0);
    }

    protected function duration(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->hasStarted()) {
                return null;
            }

            return ($this->finished_at ?? now())
                ->diffAsCarbonInterval($this->started_at)
                ->forHumans(['short' => true]);
        });
    }

    protected function isQueued(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === self::STATUS_QUEUED);
    }

    protected function isExecuting(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === self::STATUS_STARTED);
    }

    protected function isFinished(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === self::STATUS_FINISHED);
    }

    protected function isFailed(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === self::STATUS_FAILED);
    }

    protected function isEnded(): Attribute
    {
        return Attribute::get(fn (): bool => in_array($this->status, [self::STATUS_FAILED, self::STATUS_FINISHED], true));
    }

    protected function exceptionsCount(): Attribute
    {
        return Attribute::get(fn (): int => count($this->getExceptions()));
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'exceptions' => $this->exceptions ?? [],
            'output' => $this->output ?? [],
        ]);
    }
}
