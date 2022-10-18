<?php

namespace Spatie\OpenTelemetry\Support\TagProviders;

class DefaultTagsProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'host.name' => gethostname(),
        ];
    }
}
