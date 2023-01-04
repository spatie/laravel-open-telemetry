<?php

namespace Spatie\OpenTelemetry\Http\Client;

use Illuminate\Http\Client\PendingRequest;
use Spatie\OpenTelemetry\Facades\Measure as MeasureFacade;
use Spatie\OpenTelemetry\Support\Injectors\TextInjector;

class Macro
{
    public static function apply()
    {
        PendingRequest::macro('withTrace', function () {
            $headers = [];

            if ($span = MeasureFacade::currentSpan()) {
                $headers['traceparent'] = '';

                TextInjector::Inject($headers['traceparent'], $span);
            }

            return PendingRequest::withHeaders($headers);
        });
    }
}
