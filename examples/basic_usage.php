<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Tigusigalpa\TAAPI\TAAPIClient;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;

$apiSecret = getenv('TAAPI_SECRET') ?: 'YOUR_API_SECRET';
$client = new TAAPIClient($apiSecret);

echo "=== TAAPI PHP Library - Basic Usage Examples ===\n\n";

try {
    echo "1. Simple RSI Request:\n";
    $rsi = $client
        ->exchange(Exchange::BINANCE)
        ->symbol('BTC/USDT')
        ->interval(Interval::ONE_HOUR)
        ->indicator(Indicator::RSI)
        ->get();
    
    echo "   RSI Value: " . $rsi->getValue() . "\n\n";

    echo "2. EMA with Custom Period:\n";
    $ema = $client
        ->exchange('binance')
        ->symbol('BTC/USDT')
        ->interval('1h')
        ->indicator('ema')
        ->withParams(['period' => 50])
        ->get();
    
    echo "   EMA(50): " . $ema->getValue() . "\n\n";

    echo "3. MACD Request:\n";
    $macd = $client
        ->exchange(Exchange::BINANCE)
        ->symbol('ETH/USDT')
        ->interval(Interval::FOUR_HOURS)
        ->indicator(Indicator::MACD)
        ->get();
    
    echo "   MACD: " . $macd->get('valueMACD') . "\n";
    echo "   Signal: " . $macd->get('valueMACDSignal') . "\n";
    echo "   Histogram: " . $macd->get('valueMACDHist') . "\n\n";

    echo "4. Bulk Request:\n";
    $results = $client->bulk()
        ->addConstruct(
            $client->construct(Exchange::BINANCE, 'BTC/USDT', Interval::ONE_HOUR)
                ->addIndicator(Indicator::RSI, ['id' => 'btc_rsi'])
                ->addIndicator(Indicator::EMA, ['period' => 50, 'id' => 'btc_ema'])
        )
        ->addConstruct(
            $client->construct(Exchange::BINANCE, 'ETH/USDT', Interval::FOUR_HOURS)
                ->addIndicator(Indicator::SMA, ['period' => 200, 'id' => 'eth_sma'])
        )
        ->execute();
    
    echo "   Total Results: " . count($results) . "\n";
    
    $btcRsi = $results->findById('btc_rsi');
    if ($btcRsi) {
        echo "   BTC RSI: " . $btcRsi->getValue() . "\n";
    }
    
    $btcEma = $results->findById('btc_ema');
    if ($btcEma) {
        echo "   BTC EMA(50): " . $btcEma->getValue() . "\n";
    }
    
    echo "\n";

} catch (\Tigusigalpa\TAAPI\Exceptions\RateLimitException $e) {
    echo "Rate limit exceeded. Retry after: " . $e->getRetryAfterSeconds() . " seconds\n";
} catch (\Tigusigalpa\TAAPI\Exceptions\ApiException $e) {
    echo "API Error [{$e->statusCode}]: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "=== Examples Complete ===\n";
