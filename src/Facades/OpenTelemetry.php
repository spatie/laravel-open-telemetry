<?php

namespace Spatie\OpenTelemetry\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\OpenTelemetry\OpenTelemetry
 */
class OpenTelemetry extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\OpenTelemetry\OpenTelemetry::class;
    }
}
