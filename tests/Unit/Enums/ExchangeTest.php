<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Enums\Exchange;

class ExchangeTest extends TestCase
{
    public function test_exchange_values(): void
    {
        $this->assertEquals('binance', Exchange::BINANCE->value);
        $this->assertEquals('coinbase', Exchange::COINBASE->value);
        $this->assertEquals('kraken', Exchange::KRAKEN->value);
    }

    public function test_from_string(): void
    {
        $exchange = Exchange::fromString('binance');
        $this->assertEquals(Exchange::BINANCE, $exchange);
    }

    public function test_from_string_case_insensitive(): void
    {
        $exchange = Exchange::fromString('BINANCE');
        $this->assertEquals(Exchange::BINANCE, $exchange);
    }

    public function test_to_string(): void
    {
        $this->assertEquals('binance', Exchange::BINANCE->toString());
    }
}
