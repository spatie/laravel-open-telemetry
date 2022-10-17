<?php

namespace Spatie\OpenTelemetry\Drivers;

use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Support\Span;

class HttpDriver implements Driver
{
    public function sendSpan(Span $span)
    {
        Http::post('my-url', $span->toArray());
    }

    public function configure(array $options): Driver
    {
        return $this;
    }
}
