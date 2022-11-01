<?php

namespace Spatie\OpenTelemetry\Support\Samplers;

use Spatie\OpenTelemetry\Support\Lottery;

class LotterySampler extends Sampler
{
    public function shouldSample(): bool
    {
        return Lottery::odds(2, 100)->choose();
    }
}
