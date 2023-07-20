<?php

namespace ViicSlen\TrackableTasks\Testing\Fakes;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\PendingBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use RuntimeException;
use ViicSlen\TrackableTasks\Concerns\TrackAutomatically;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Facades;

class TrackableTasksFake
{
    protected array $trackedTasks = [];

    protected function isEvent($trackable): bool
    {
        return $trackable instanceof JobProcessing
            || $trackable instanceof JobProcessed
            || $trackable instanceof JobFailed
            || $trackable instanceof JobExceptionOccurred;
    }

    protected function isQueableOrEvent($trackable): bool
    {
        return $this->isEvent($trackable)
            || $trackable instanceof ShouldQueue
            || $trackable instanceof PendingBatch
            || $trackable instanceof PendingDispatch;
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

    protected function getModelDetails(Model $model): array
    {
        return array_filter([
            'trackable_id' => $model->getKey(),
            'type' => TrackableTask::TYPE_MODEL,
            'name' => $model->getMorphClass(),
        ]);
    }

    public function getTaskId($job): ?int
    {
        $uses = array_flip(class_uses_recursive($job));

        return isset($uses[TrackAutomatically::class]) || method_exists($job, 'getTaskId')
            ? $job->getTaskId()
            : null;
    }

    public function getTask($trackable): ?TrackableTask
    {
        $job = $this->isEvent($trackable) ? $this->getEventJob($trackable) : $trackable;

        if (! ($id = $this->getTaskId($job))) {
            return null;
        }

        return $this->trackedTasks[$id] ?? null;
    }

    public function createTask(array|string $data): TrackableTask
    {
        /** @var TrackableTask $class */
        $class = app(TrackableTask::class);

        if (is_string($data)) {
            $data = ['name' => $data];
        }

        $task = $class::create($data);

        $this->trackedTasks[$task->id] = $task;

        return $task;
    }

    public function createTaskFrom($trackable, $data): TrackableTask
    {
        $data = array_merge(match (true) {
            $this->isQueableOrEvent($trackable) => $this->getJobDetails($trackable),
            $trackable instanceof Model => $this->getModelDetails($trackable),
            default => throw new RuntimeException(sprintf('Unsupported trackable type [%s]', get_class($trackable))),
        }, $data);

        return Facades\TrackableTasks::createTask($data);
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
            if ($job->batch()?->finished() || $job->batch()?->cancelled()) {
                $data['status'] = $job->batch()?->hasFailures() ? TrackableTask::STATUS_FAILED : TrackableTask::STATUS_FINISHED;
            } else {
                unset($data['status']);
            }
        }

        $updated = $task->update(array_merge($this->getJobDetails($job), $data));

        $this->trackedTasks[$task->id] = $task;

        return $updated;
    }

    public function addTaskException($trackable, mixed $exception): bool
    {
        $job = $this->isEvent($trackable) ? $this->getEventJob($trackable) : $trackable;

        if (! ($task = $this->getTask($job))) {
            return false;
        }

        return $task->addException($exception);
    }

    public function batch(mixed $jobs, string $name = null): PendingBatch
    {
        $jobs =  Collection::wrap($jobs);

        $taskName = $name ?? ($jobs->first() ? get_class($jobs->first()) : 'Batch');
        $task = Facades\TrackableTasks::createTask($taskName);

        $trackableJobs = $jobs->map(function ($job) use ($task) {
            $uses = array_flip(class_uses_recursive($job));

            if (isset($uses[TrackAutomatically::class]) && method_exists($job, 'setTaskId')) {
                $job->setTaskId($task->id);
            }

            return $job;
        });

        $batch = Bus::fake()->batch($trackableJobs);

        if ($name) {
            $batch->name($name);
        }

        return $batch;
    }
}
