<?php

namespace Spatie\OpenTelemetry\Drivers;

use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Support\Span;

class RayDriver extends HttpDriver
{
    protected array $options = [];

    public function sendSpan(Span $span)
    {
        $payload = [$span->toArray()];

        Http::asJson()->post($this->options['url'] ?? 'http://localhost:23517/otel', $payload);
    }
}
