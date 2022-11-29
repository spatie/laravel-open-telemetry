<?php

namespace Spatie\OpenTelemetry\Http\Client;

use Illuminate\Http\Client\PendingRequest;
use Spatie\OpenTelemetry\Facades\Measure as MeasureFacade;

class Macro
{
    public static function apply() {
        PendingRequest::macro('withTrace', function () {
            return PendingRequest::withHeaders([
                'traceparent' => MeasureFacade::currentSpan()->toTraceContextID(),
            ]);
        });
    }
}
