<?php

namespace Spatie\OpenTelemetry\Http\Client;

use Illuminate\Http\Client\PendingRequest;
use Spatie\OpenTelemetry\Facades\Measure as MeasureFacade;
use Spatie\OpenTelemetry\Support\Injectors\ArrayInjector;

class Macro
{
    public static function apply() {
        PendingRequest::macro('withTrace', function () {
            $headers = [];

            if ($span = MeasureFacade::currentSpan()) {
                ArrayInjector::Inject($headers, $span);
            }

            return PendingRequest::withHeaders($headers);
        });
    }
}
