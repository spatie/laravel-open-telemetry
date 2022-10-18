<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Carbon\Carbon;
use Spatie\OpenTelemetry\Support\StopWatch;

class FakeStopWatch extends StopWatch
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
