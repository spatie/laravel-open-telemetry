<?php

namespace Spatie\OpenTelemetry\Support;

use Exception;

class Stopwatch
{
    protected ?int $startTime = null;

    protected ?int $stopTime = null;

    public function start(): self
    {
        $this->startTime = now()->getPreciseTimestamp();

        return $this;
    }

    public function stop(): self
    {
        $this->stopTime = now()->getPreciseTimestamp();

        return $this;
    }

    public function startTime(): int
    {
        return $this->startTime;
    }

    public function stopTime(): ?int
    {
        return $this->stopTime;
    }

    public function elapsedTime(): int
    {
        if (is_null($this->startTime)) {
            throw new Exception('//TODO: add message');
        }

        if (is_null($this->stopTime)) {
            throw new Exception('//TODO: add message');
        }

        return $this->stopTime - $this->startTime;
    }
}
