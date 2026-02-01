<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\TAAPIClient;

class TAAPIIntegrationTest extends TestCase
{
    private TAAPIClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $apiSecret = getenv('TAAPI_SECRET');
        
        if (empty($apiSecret) || $apiSecret === 'test_secret_key') {
            $this->markTestSkipped('TAAPI_SECRET not configured for integration tests');
        }
        
        $this->client = new TAAPIClient($apiSecret);
    }

    public function test_direct_request_with_rsi(): void
    {
        $response = $this->client
            ->exchange(Exchange::BINANCE)
            ->symbol('BTC/USDT')
            ->interval(Interval::ONE_HOUR)
            ->indicator(Indicator::RSI)
            ->get();

        $this->assertNotNull($response);
        $this->assertEquals('rsi', $response->indicator);
        $this->assertTrue($response->has('value'));
        $this->assertIsFloat($response->getValue());
    }

    public function test_bulk_request(): void
    {
        $results = $this->client->bulk()
            ->addConstruct(
                $this->client->construct(Exchange::BINANCE, 'BTC/USDT', Interval::ONE_HOUR)
                    ->addIndicator(Indicator::RSI, ['id' => 'btc_rsi'])
                    ->addIndicator(Indicator::EMA, ['period' => 50, 'id' => 'btc_ema'])
            )
            ->execute();

        $this->assertCount(2, $results);
        
        $rsi = $results->findById('btc_rsi');
        $this->assertNotNull($rsi);
        $this->assertEquals('rsi', $rsi->indicator);
        
        $ema = $results->findById('btc_ema');
        $this->assertNotNull($ema);
        $this->assertEquals('ema', $ema->indicator);
    }

    public function test_manual_request(): void
    {
        $candles = [
            [1609459200, 28923.63, 28923.63, 28923.63, 28923.63, 0.00000000],
            [1609462800, 29083.37, 29188.78, 28963.64, 29103.37, 1107.05626800],
            [1609466400, 29103.38, 29152.98, 28980.01, 29050.00, 978.58108600],
            [1609470000, 29050.01, 29071.99, 28852.01, 28852.02, 1223.86114900],
            [1609473600, 28852.02, 29034.99, 28827.00, 28961.00, 1239.48652100],
            [1609477200, 28961.01, 29150.00, 28943.00, 29099.99, 916.25317300],
            [1609480800, 29099.98, 29188.00, 29027.14, 29159.99, 848.81565000],
            [1609484400, 29160.00, 29289.99, 29115.00, 29250.00, 1042.33776900],
            [1609488000, 29250.01, 29377.77, 29217.78, 29377.76, 1277.13690800],
            [1609491600, 29377.77, 29480.00, 29341.00, 29374.99, 1275.91266700],
            [1609495200, 29375.00, 29432.99, 29320.00, 29432.98, 770.12843100],
            [1609498800, 29432.99, 29600.00, 29415.00, 29600.00, 1361.93171000],
            [1609502400, 29600.01, 29617.78, 29470.00, 29546.63, 1095.14694200],
            [1609506000, 29546.64, 29546.64, 29200.00, 29288.00, 1868.26219600],
            [1609509600, 29288.01, 29315.00, 29101.00, 29196.00, 1448.38314900],
        ];

        $response = $this->client
            ->manual(Indicator::EMA)
            ->withCandles($candles)
            ->withParams(['period' => 10])
            ->execute();

        $this->assertNotNull($response);
        $this->assertEquals('ema', $response->indicator);
        $this->assertTrue($response->has('value'));
        $this->assertIsFloat($response->getValue());
    }
}
