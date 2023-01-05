<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class FakeTagsProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'host.name' => 'static.host.name.for.tests',
        ];
    }
}
