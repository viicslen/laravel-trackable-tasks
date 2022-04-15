<?php

namespace ViicSlen\TrackableTasks;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\PendingBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use ViicSlen\TrackableTasks\Concerns\Trackable;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;

class TrackableTasks
{
    protected string $connection;

    public function __construct()
    {
        $this->connection = config('trackable-task.database.connection', config('database.default', 'mysql'));
    }

    protected function isEvent($trackable): bool
    {
        return $trackable instanceof JobProcessing
            || $trackable instanceof JobProcessed
            || $trackable instanceof JobFailed
            || $trackable instanceof JobExceptionOccurred;
    }

    protected function getEventJob(JobProcessing|JobProcessed|JobFailed|JobExceptionOccurred $event): ShouldQueue
    {
        $payload = $event->job->payload();

        return unserialize($payload['data']['command'], ['allowed_classes' => true]);
    }

    protected function getJobDetails($job): array
    {
        $uses = array_flip(class_uses_recursive($job));

        if (isset($uses[Batchable::class]) && $job->batching()) {
            return array_filter([
                'trackable_id' => $job->batchId,
                'type' => TrackableTask::TYPE_BATCH,
                'name' => $job->batch()?->name ?? get_class($job),
                'queue' => $job->queue ?? null,
                'attempts' => method_exists($job, 'attempts') ? $job->attempts() : null,
            ]);
        }

        return array_filter([
            'trackable_id' => $job->job?->getJobId(),
            'type' => TrackableTask::TYPE_JOB,
            'name' => method_exists($job, 'displayName') ? $job->displayName() : get_class($job),
            'queue' => $job->queue ?? null,
            'attempts' => method_exists($job, 'attempts') ? $job->attempts() : null,
        ]);
    }

    public function getTaskId($job): ?int
    {
        $uses = array_flip(class_uses_recursive($job));

        return isset($uses[Trackable::class]) || method_exists($job, 'getTaskId')
            ? $job->getTaskId()
            : null;
    }

    public function getTask($trackable): ?TrackableTask
    {
        $job = $this->isEvent($trackable) ? $this->getEventJob($trackable) : $trackable;

        if (! ($id = $this->getTaskId($job))) {
            return null;
        }

        $class = app(TrackableTask::class);

        return $class::on($this->connection)
            ->whereKey($id)
            ->first();
    }

    public function createTask($trackable, $data): TrackableTask
    {
        /** @var TrackableTask $class */
        $class = app(TrackableTask::class);
        $data = array_merge($this->getJobDetails($trackable), $data);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $class::on($this->connection)->create($data);
    }

    public function updateTask($trackable, array $data): bool
    {
        $job = $this->isEvent($trackable) ? $this->getEventJob($trackable) : $trackable;

        if (! ($task = $this->getTask($job))) {
            return false;
        }

        if (isset($data['status']) && $data['status'] === TrackableTask::STATUS_FINISHED && $task->hasFailed()) {
            unset($data['status']);
        }

        if (isset($data['status'])
            && in_array($data['status'], [TrackableTask::STATUS_FINISHED, TrackableTask::STATUS_FAILED], true)
            && method_exists($job, 'batching')
            && $job->batching()) {

            /** @noinspection NullPointerExceptionInspection */
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if ($job->batch()->finished() || $job->batch()->cancelled()) {
                /** @noinspection NullPointerExceptionInspection */
                /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                $data['status'] = $job->batch()->hasFailures() ? TrackableTask::STATUS_FAILED : TrackableTask::STATUS_FINISHED;
            } else {
                unset($data['status']);
            }
        }

        return $task->update(array_merge($this->getJobDetails($job), $data));
    }

    public function batch(mixed $jobs, string $name = null): PendingBatch
    {
        $jobs = Collection::wrap($jobs);
        $taskName = $name ?? ($jobs->first() ? get_class($jobs->first()) : 'Batch');
        $task = app(TrackableTask::class)::create(['name' => $taskName]);
        $batch = Bus::batch($jobs->map(function ($job) use ($task) {
            $uses = array_flip(class_uses_recursive($job));

            if (isset($uses[Trackable::class]) && method_exists($job, 'setTaskId')) {
                $job->setTaskId($task->id);
            }

            return $job;
        }));

        if ($name) {
            $batch->name($name);
        }

        return $batch;
    }
}
