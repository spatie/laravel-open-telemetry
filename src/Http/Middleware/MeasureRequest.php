<?php

namespace Spatie\OpenTelemetry\Http\Middleware;

use Closure;
use Spatie\OpenTelemetry\Facades\Measure;

class MeasureRequest
{
    public function handle($request, Closure $next)
    {
        Measure::start('request');

        return $next($request);
    }

    public function terminate($request, $response)
    {
        Measure::stop('request');
    }
}
