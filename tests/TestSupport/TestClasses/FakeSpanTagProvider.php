<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Illuminate\Support\Str;
use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class FakeSpanTagProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'my-span-tag' => Str::random(),
        ];
    }
}
