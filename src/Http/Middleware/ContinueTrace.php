<?php

namespace Spatie\OpenTelemetry\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Support\ParsedTraceParentHeaderValue;

class ContinueTrace
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->hasHeader('traceparent')) {
            $next($request);
        }

        $headerValue = $request->header('traceparent');

        if (! $parsedHeader = ParsedTraceParentHeaderValue::make($headerValue)) {
            return $next($request);
        }

        Measure::setTraceId($parsedHeader->traceId);

        return $next($request);
    }
}
