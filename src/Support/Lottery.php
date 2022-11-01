<?php

namespace Spatie\OpenTelemetry\Support;

class Lottery
{
    public static function odds(int $chances, int $outOf): self
    {
        return new static($chances, $outOf);
    }

    public function __construct(
        protected int $chances,
        protected int $outOf,
    ) {
    }

    public function choose(): bool
    {
        return random_int(1, $this->outOf) <= $this->chances;
    }
}
