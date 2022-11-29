<?php

namespace Spatie\OpenTelemetry\Http\Client;

use Illuminate\Http\Client\PendingRequest;
use Spatie\OpenTelemetry\Facades\Measure as MeasureFacade;

class Macro
{
    public static function apply() {
        PendingRequest::macro('withTrace', function () {
            $headers = [];

            if ($traceContextID = MeasureFacade::currentSpan()?->toTraceContextID()) {
                $headers['traceparent'] = $traceContextID;
            }

            return PendingRequest::withHeaders($headers);
        });
    }
}
