<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit\Builders;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Builders\DirectBuilder;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;
use Tigusigalpa\TAAPI\Http\RequestBuilder;
use Tigusigalpa\TAAPI\Http\ResponseHandler;

class DirectBuilderTest extends TestCase
{
    private DirectBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        
        $requestBuilder = $this->createMock(RequestBuilder::class);
        $responseHandler = $this->createMock(ResponseHandler::class);
        
        $this->builder = new DirectBuilder($requestBuilder, $responseHandler);
    }

    public function test_fluent_interface(): void
    {
        $result = $this->builder
            ->exchange('binance')
            ->symbol('BTC/USDT')
            ->interval('1h')
            ->indicator('rsi');

        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_exchange_accepts_string(): void
    {
        $result = $this->builder->exchange('binance');
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_exchange_accepts_enum(): void
    {
        $result = $this->builder->exchange(Exchange::BINANCE);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_interval_accepts_string(): void
    {
        $result = $this->builder->interval('1h');
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_interval_accepts_enum(): void
    {
        $result = $this->builder->interval(Interval::ONE_HOUR);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_indicator_accepts_string(): void
    {
        $result = $this->builder->indicator('rsi');
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_indicator_accepts_enum(): void
    {
        $result = $this->builder->indicator(Indicator::RSI);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_with_params(): void
    {
        $result = $this->builder->withParams(['period' => 14]);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_with_param(): void
    {
        $result = $this->builder->withParam('period', 14);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_backtrack(): void
    {
        $result = $this->builder->backtrack(5);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_backtracks(): void
    {
        $result = $this->builder->backtracks(10);
        $this->assertInstanceOf(DirectBuilder::class, $result);
    }

    public function test_get_throws_exception_when_exchange_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required parameter: exchange');

        $this->builder
            ->symbol('BTC/USDT')
            ->interval('1h')
            ->indicator('rsi')
            ->get();
    }

    public function test_get_throws_exception_when_symbol_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required parameter: symbol');

        $this->builder
            ->exchange('binance')
            ->interval('1h')
            ->indicator('rsi')
            ->get();
    }

    public function test_get_throws_exception_when_interval_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required parameter: interval');

        $this->builder
            ->exchange('binance')
            ->symbol('BTC/USDT')
            ->indicator('rsi')
            ->get();
    }

    public function test_get_throws_exception_when_indicator_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required parameter: indicator');

        $this->builder
            ->exchange('binance')
            ->symbol('BTC/USDT')
            ->interval('1h')
            ->get();
    }
}
