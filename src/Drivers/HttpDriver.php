<?php

namespace Spatie\OpenTelemetry\Drivers;

use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Support\Span;

class HttpDriver implements Driver
{
    protected array $options = [];

    public function sendSpan(Span $span)
    {
        $payload = [$span->toArray()];

        $response = Http::asJson()->post($this->options['url'], $payload);

        ray($response->status())->purple();
    }

    public function configure(array $options): Driver
    {
        $this->options = $options;

        return $this;
    }
}
