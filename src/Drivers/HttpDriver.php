<?php

namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

class HttpDriver implements Driver
{
    public function sendSpan(Span $span)
    {
        dump($span->toArray());
    }

    public function configure(array $options): Driver
    {
        return $this;
    }
}
