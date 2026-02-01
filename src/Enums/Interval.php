<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Enums;

enum Interval: string
{
    case ONE_MINUTE = '1m';
    case FIVE_MINUTES = '5m';
    case FIFTEEN_MINUTES = '15m';
    case THIRTY_MINUTES = '30m';
    case ONE_HOUR = '1h';
    case TWO_HOURS = '2h';
    case FOUR_HOURS = '4h';
    case TWELVE_HOURS = '12h';
    case ONE_DAY = '1d';
    case ONE_WEEK = '1w';

    public static function fromString(string $interval): self
    {
        return self::from(strtolower($interval));
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toMinutes(): int
    {
        return match($this) {
            self::ONE_MINUTE => 1,
            self::FIVE_MINUTES => 5,
            self::FIFTEEN_MINUTES => 15,
            self::THIRTY_MINUTES => 30,
            self::ONE_HOUR => 60,
            self::TWO_HOURS => 120,
            self::FOUR_HOURS => 240,
            self::TWELVE_HOURS => 720,
            self::ONE_DAY => 1440,
            self::ONE_WEEK => 10080,
        };
    }
}
