<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Enums\Interval;

class IntervalTest extends TestCase
{
    public function test_interval_values(): void
    {
        $this->assertEquals('1m', Interval::ONE_MINUTE->value);
        $this->assertEquals('1h', Interval::ONE_HOUR->value);
        $this->assertEquals('1d', Interval::ONE_DAY->value);
    }

    public function test_from_string(): void
    {
        $interval = Interval::fromString('1h');
        $this->assertEquals(Interval::ONE_HOUR, $interval);
    }

    public function test_to_string(): void
    {
        $this->assertEquals('1h', Interval::ONE_HOUR->toString());
    }

    public function test_to_minutes(): void
    {
        $this->assertEquals(1, Interval::ONE_MINUTE->toMinutes());
        $this->assertEquals(60, Interval::ONE_HOUR->toMinutes());
        $this->assertEquals(1440, Interval::ONE_DAY->toMinutes());
    }
}
