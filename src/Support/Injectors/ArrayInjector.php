<?php

namespace Spatie\OpenTelemetry\Support\Injectors;

use Spatie\OpenTelemetry\Support\Span;

class ArrayInjector
{
    static public function Inject(array &$data, Span $span, ?string $key = null): void
    {
        if (is_null($key)) {
            $key = config('open-telemetry.injectors.array.key', 'traceparent');
        }

        TextInjector::Inject($data[$key], $span);
    }
}
