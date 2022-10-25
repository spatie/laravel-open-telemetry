<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\SDK\Trace\RandomIdGenerator;

class IdGenerator
{
    public function traceId(): string
    {
        return (new RandomIdGenerator())->generateTraceId();
    }

    public function spanId(): string
    {
        return (new RandomIdGenerator())->generateSpanId();
    }
}
