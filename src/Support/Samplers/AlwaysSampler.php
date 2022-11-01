<?php

namespace Spatie\OpenTelemetry\Support\Samplers;

class AlwaysSampler extends Sampler
{
    public function shouldSample()
    {
        return true;
    }
}
