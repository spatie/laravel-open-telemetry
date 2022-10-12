<?php

use Spatie\OpenTelemetry\Facades\Measure;


it('can test', function () {
   Measure::start('hey');

    Measure::stop('hey');

});
