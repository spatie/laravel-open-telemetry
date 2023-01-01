<?php

namespace Spatie\OpenTelemetry\Support\Injectors;

use Spatie\OpenTelemetry\Support\Span;

class TextInjector
{
    static public function Inject(string &$data, Span $span): void
    {
        $data = sprintf(
            '%s-%s-%s-%02x',
            '00',
            $span->trace()->id(),
            $span->id(),
            $span->flags(),
        );
    }
}
