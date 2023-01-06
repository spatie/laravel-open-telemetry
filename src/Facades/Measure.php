<?php

namespace Spatie\OpenTelemetry\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Spatie\OpenTelemetry\Facades\Measure
 */
class Measure extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\OpenTelemetry\Support\Measure::class;
    }
}
