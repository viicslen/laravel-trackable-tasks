<?php

namespace ViicSlen\TrackableTasks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use ViicSlen\TrackableTasks\Contracts\TrackableTask;
use ViicSlen\TrackableTasks\Models\TrackedTask;

class TrackedTaskFactory extends Factory
{
    protected $model = TrackedTask::class;

    public function definition(): array
    {
        return [
            'type' => Arr::random([TrackableTask::TYPE_BATCH, TrackableTask::TYPE_JOB]),
            'name' => $this->faker->userName,
            'queue' => 'default',
            'status' => TrackableTask::STATUS_QUEUED,
            'message' => $this->faker->realText(),
            'created_at' => now(),
        ];
    }
}
