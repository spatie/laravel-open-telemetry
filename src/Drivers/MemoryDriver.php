<?php

namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

class MemoryDriver implements Driver
{
    /** @var array<int, Span> */
    public array $sentSpans = [];

    public function sendSpan(Span $span)
    {
        $this->sentSpans[] = $span;
    }

    public function configure(array $options): Driver
    {
        return $this;
    }

    public function allPayloads(): array
    {
        return [
            'sentSpans' => collect($this->sentSpans)->map->toArray()->toArray(),
        ];
    }
}
