<?php

namespace ViicSlen\TrackableTasks\Types;

use ViicSlen\TrackableTasks\Contracts\TrackableType as Contract;

/**
 * @template T
 */
abstract class TrackableType implements Contract
{
    public const TYPE = '';

    /**
     * @var T $trackable
     */
    public readonly mixed $trackable;

    /**
     * @param  T  $trackable
     */
    public function __construct(mixed $trackable) {
        $this->trackable = $trackable;
    }

    public function getName(): string
    {
        return get_class($this->trackable);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getQueue(): ?string
    {
        return null;
    }

    public function getAttempts(): ?int
    {
        return null;
    }

    public function toArray() :array
    {
        return [
            'trackable_id' => $this->getKey(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'queue' => $this->getQueue(),
            'attempts' => $this->getAttempts(),
        ];
    }
}
