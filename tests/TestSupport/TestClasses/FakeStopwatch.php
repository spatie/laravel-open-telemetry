<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Carbon\Carbon;
use Spatie\OpenTelemetry\Support\Stopwatch;

class FakeStopwatch extends Stopwatch
{
    public function start(): self
    {
        $this->startTime = Carbon::now()->timestamp;

        return $this;
    }

    public function stop(): self
    {
        $this->stopTime = Carbon::now()->timestamp;

        return $this;
    }
}
