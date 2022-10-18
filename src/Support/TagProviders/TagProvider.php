<?php

namespace Spatie\OpenTelemetry\Support\TagProviders;

interface TagProvider
{
    /**
     * @return array<string, mixed>
     */
    public function tags(): array;
}
