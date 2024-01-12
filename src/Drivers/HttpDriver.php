<?php

namespace Spatie\OpenTelemetry\Drivers;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Support\Span;

class HttpDriver implements Driver
{
    protected array $options = [];

    public function sendSpan(Span $span)
    {
        $payload = [$span->toArray()];

        $promise = Http::async()->asJson()
            ->withHeaders($this->options['headers'] ?? [])
            ->when(isset($this->options['basic_auth']), function (Factory $client) {
                $client->withBasicAuth(
                    $this->options['basic_auth']['username'],
                    $this->options['basic_auth']['password'],
                );
            })
            ->post($this->options['url'], $payload);

        $promise->wait();
    }

    public function configure(array $options): Driver
    {
        $this->options = $options;

        return $this;
    }
}
