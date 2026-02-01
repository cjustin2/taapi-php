<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Builders\BulkBuilder;
use Tigusigalpa\TAAPI\Builders\ConstructBuilder;
use Tigusigalpa\TAAPI\Builders\DirectBuilder;
use Tigusigalpa\TAAPI\Builders\ManualBuilder;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\TAAPIClient;

class TAAPIClientTest extends TestCase
{
    private TAAPIClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new TAAPIClient('test_secret');
    }

    public function test_client_initialization(): void
    {
        $this->assertInstanceOf(TAAPIClient::class, $this->client);
        $this->assertEquals('test_secret', $this->client->getApiSecret());
    }

    public function test_exchange_returns_direct_builder(): void
    {
        $builder = $this->client->exchange('binance');
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_exchange_accepts_enum(): void
    {
        $builder = $this->client->exchange(Exchange::BINANCE);
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_symbol_returns_direct_builder(): void
    {
        $builder = $this->client->symbol('BTC/USDT');
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_interval_returns_direct_builder(): void
    {
        $builder = $this->client->interval('1h');
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_interval_accepts_enum(): void
    {
        $builder = $this->client->interval(Interval::ONE_HOUR);
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_indicator_returns_direct_builder(): void
    {
        $builder = $this->client->indicator('rsi');
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_indicator_accepts_enum(): void
    {
        $builder = $this->client->indicator(Indicator::RSI);
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_direct_returns_direct_builder(): void
    {
        $builder = $this->client->direct();
        $this->assertInstanceOf(DirectBuilder::class, $builder);
    }

    public function test_bulk_returns_bulk_builder(): void
    {
        $builder = $this->client->bulk();
        $this->assertInstanceOf(BulkBuilder::class, $builder);
    }

    public function test_construct_returns_construct_builder(): void
    {
        $builder = $this->client->construct('binance', 'BTC/USDT', '1h');
        $this->assertInstanceOf(ConstructBuilder::class, $builder);
    }

    public function test_construct_accepts_enums(): void
    {
        $builder = $this->client->construct(
            Exchange::BINANCE,
            'BTC/USDT',
            Interval::ONE_HOUR
        );
        $this->assertInstanceOf(ConstructBuilder::class, $builder);
    }

    public function test_manual_returns_manual_builder(): void
    {
        $builder = $this->client->manual('ema');
        $this->assertInstanceOf(ManualBuilder::class, $builder);
    }

    public function test_manual_accepts_enum(): void
    {
        $builder = $this->client->manual(Indicator::EMA);
        $this->assertInstanceOf(ManualBuilder::class, $builder);
    }

    public function test_set_timeout(): void
    {
        $result = $this->client->setTimeout(60);
        $this->assertInstanceOf(TAAPIClient::class, $result);
    }
}
