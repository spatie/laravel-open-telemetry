<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\SDK\Trace\RandomIdGenerator;

class IdGenerator
{
    public function traceId(): string
    {
        ray('random trace id used')->purple();
        return (new RandomIdGenerator())->generateTraceId();
    }

    public function spanId(): string
    {
        return (new RandomIdGenerator())->generateSpanId();
    }
}
