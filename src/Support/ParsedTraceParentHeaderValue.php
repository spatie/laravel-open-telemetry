<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\API\Trace\Propagation\TraceContextValidator;
use OpenTelemetry\API\Trace\SpanContext;
use OpenTelemetry\API\Trace\SpanContextValidator;

class ParsedTraceParentHeaderValue
{
    public static function make(string $headerValue): ?self
    {
        if (self::isValidHeaderValue($headerValue)) {
            return null;
        }

        [$version, $traceId, $spanId, $flags] = explode('-', $headerValue);
        if ($version !== '00') {
            return null;
        }

        if (! SpanContextValidator::isValidTraceId($traceId)) {
            return null;
        }

        if (! SpanContextValidator::isValidSpanId($spanId)) {
            return null;
        }

        if (! TraceContextValidator::isValidTraceFlag($flags)) {
            return null;
        }

        return new self($version, $traceId, $spanId, $flags);
    }

    public function __construct(
        public string $version,
        public string $traceId,
        public string $spanId,
        public string $flags,
    ) {
    }

    public static function isValidHeaderValue(string $headerValue): bool
    {
        return substr_count($headerValue, '-') !== 3;
    }
}
