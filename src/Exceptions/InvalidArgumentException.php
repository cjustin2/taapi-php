<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Exceptions;

class InvalidArgumentException extends TAAPIException
{
    public static function missingParameter(string $parameter): self
    {
        return new self("Missing required parameter: {$parameter}");
    }

    public static function invalidValue(string $parameter, mixed $value): self
    {
        $valueStr = is_scalar($value) ? (string)$value : gettype($value);
        return new self("Invalid value for parameter '{$parameter}': {$valueStr}");
    }

    public static function invalidExchange(string $exchange): self
    {
        return new self("Invalid exchange: {$exchange}");
    }

    public static function invalidInterval(string $interval): self
    {
        return new self("Invalid interval: {$interval}");
    }

    public static function invalidIndicator(string $indicator): self
    {
        return new self("Invalid indicator: {$indicator}");
    }
}
