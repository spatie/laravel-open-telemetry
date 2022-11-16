<?php

namespace Spatie\OpenTelemetry\Http\Middleware;

use Closure;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Support\ParsedTraceParentHeaderValue;

class ContinueTrace
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
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
