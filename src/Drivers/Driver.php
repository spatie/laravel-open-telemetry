<?php

namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

interface Driver
{
    /**
     * All options set for this driver in the config file will be passed
     * to this method.
     *
     * @param  array<string, string>  $options
     * @return $this
     */
    public function configure(array $options): self;

    public function sendSpan(Span $span);
}
