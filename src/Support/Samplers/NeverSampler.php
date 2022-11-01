<?php

namespace Spatie\OpenTelemetry\Support\Samplers;

class NeverSampler extends Sampler
{
    public function shouldSample()
    {
        return false;
    }
}
