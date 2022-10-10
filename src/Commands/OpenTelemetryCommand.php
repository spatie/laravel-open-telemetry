<?php

namespace Spatie\OpenTelemetry\Commands;

use Illuminate\Console\Command;

class OpenTelemetryCommand extends Command
{
    public $signature = 'laravel-open-telemetry';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
