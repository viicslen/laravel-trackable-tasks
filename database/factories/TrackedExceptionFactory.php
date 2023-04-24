<?php

namespace ViicSlen\TrackableTasks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ViicSlen\TrackableTasks\Enums\ExceptionSeverity;
use ViicSlen\TrackableTasks\Models\TrackedException;

class TrackedExceptionFactory extends Factory
{
    protected $model = TrackedException::class;

    public function definition(): array
    {
        return [
            'severity' => $this->faker->randomElement(ExceptionSeverity::cases()),
            'message' => $this->faker->text(),
        ];
    }
}
