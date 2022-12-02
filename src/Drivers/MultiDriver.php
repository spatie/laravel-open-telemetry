<?php

namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

class MultiDriver implements Driver
{
    /** @var array<int, Driver> */
    protected array $drivers = [];

    public function addDriver(Driver $driver): self
    {
        $this->drivers[] = $driver;

        return $this;
    }

    public function sendSpan(Span $span): self
    {
        foreach ($this->drivers as $driver) {
            $driver->sendSpan($span);
        }

        return $this;
    }

    public function configure(array $options): Driver
    {
        return $this;
    }
}
