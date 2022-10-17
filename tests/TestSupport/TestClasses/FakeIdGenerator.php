<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Spatie\OpenTelemetry\Support\IdGenerator;

class FakeIdGenerator extends IdGenerator
{
    protected static int $traceId = 0;

    protected static int $spanId = 0;

    public static function reset()
    {
        self::$traceId = 0;

        self::$spanId = 0;
    }

    public function traceId(): string
    {
        return (string) self::$traceId++;
    }

    public function spanId(): string
    {
        return (string) self::$spanId++;
    }
}
