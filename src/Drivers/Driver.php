<?php

namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

interface Driver
{
    public function configure(array $options): self;

    public function sendSpan(Span $span);
}
