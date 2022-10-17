<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\SDK\Trace\RandomIdGenerator;

class Trace
{
    public static function start(string $id = null, string $name = ''): self
    {
        return new self($id, $name);
    }

    public function __construct(protected ?string $id = null, protected ?string $name)
    {
        $this->id ??= app(IdGenerator::class)->spanId();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
