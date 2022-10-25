<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Illuminate\Support\Str;
use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class FakeTraceTagProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'my-trace-tag' => Str::random(),
        ];
    }
}
