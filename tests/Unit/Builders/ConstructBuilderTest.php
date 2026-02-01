<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit\Builders;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Builders\ConstructBuilder;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;

class ConstructBuilderTest extends TestCase
{
    public function test_add_indicator_with_string(): void
    {
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $result = $builder->addIndicator('rsi');
        
        $this->assertInstanceOf(ConstructBuilder::class, $result);
    }

    public function test_add_indicator_with_enum(): void
    {
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $result = $builder->addIndicator(Indicator::RSI);
        
        $this->assertInstanceOf(ConstructBuilder::class, $result);
    }

    public function test_add_indicator_with_params(): void
    {
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $builder->addIndicator('rsi', ['period' => 14, 'id' => 'test_rsi']);
        
        $array = $builder->toArray();
        
        $this->assertCount(1, $array['indicators']);
        $this->assertEquals('rsi', $array['indicators'][0]['indicator']);
        $this->assertEquals(14, $array['indicators'][0]['period']);
        $this->assertEquals('test_rsi', $array['indicators'][0]['id']);
    }

    public function test_to_array_with_string_values(): void
    {
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $builder->addIndicator('rsi');
        $builder->addIndicator('macd');
        
        $array = $builder->toArray();
        
        $this->assertEquals('binance', $array['exchange']);
        $this->assertEquals('BTC/USDT', $array['symbol']);
        $this->assertEquals('1h', $array['interval']);
        $this->assertCount(2, $array['indicators']);
    }

    public function test_to_array_with_enums(): void
    {
        $builder = new ConstructBuilder(
            Exchange::BINANCE,
            'BTC/USDT',
            Interval::ONE_HOUR
        );
        $builder->addIndicator(Indicator::RSI);
        
        $array = $builder->toArray();
        
        $this->assertEquals('binance', $array['exchange']);
        $this->assertEquals('1h', $array['interval']);
        $this->assertEquals('rsi', $array['indicators'][0]['indicator']);
    }

    public function test_to_array_throws_exception_when_no_indicators(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required parameter: indicators');
        
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $builder->toArray();
    }

    public function test_multiple_indicators(): void
    {
        $builder = new ConstructBuilder('binance', 'BTC/USDT', '1h');
        $builder
            ->addIndicator('rsi', ['period' => 14])
            ->addIndicator('ema', ['period' => 50])
            ->addIndicator('macd');
        
        $array = $builder->toArray();
        
        $this->assertCount(3, $array['indicators']);
        $this->assertEquals('rsi', $array['indicators'][0]['indicator']);
        $this->assertEquals('ema', $array['indicators'][1]['indicator']);
        $this->assertEquals('macd', $array['indicators'][2]['indicator']);
    }
}
