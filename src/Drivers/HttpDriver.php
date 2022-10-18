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

        $payload[0]['tags'] = [
            'host.name' => 'freeks-MacBook-Pro.local',
        ];

        Http::asJson()->async()->post($this->options['url'], $payload);
    }

    public function configure(array $options): Driver
    {
        $this->options = $options;

        return $this;
    }
}
