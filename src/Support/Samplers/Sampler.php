<?php

namespace Spatie\OpenTelemetry\Support\Samplers;

abstract class Sampler
{
    abstract public function shouldSample();
}
