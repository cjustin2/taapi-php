<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Tigusigalpa\TAAPI\TAAPIClient;
use Tigusigalpa\TAAPI\Enums\Indicator;

$apiSecret = getenv('TAAPI_SECRET') ?: 'YOUR_API_SECRET';
$client = new TAAPIClient($apiSecret);

echo "=== Manual Candles Example ===\n\n";

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

echo "Candle data format: [timestamp, open, high, low, close, volume]\n";
echo "Total candles: " . count($candles) . "\n\n";

try {
    echo "1. Calculate EMA(10):\n";
    $ema = $client
        ->manual(Indicator::EMA)
        ->withCandles($candles)
        ->withParams(['period' => 10])
        ->execute();
    
    echo "   EMA(10): " . $ema->getValue() . "\n\n";

    echo "2. Calculate RSI(14):\n";
    $rsi = $client
        ->manual(Indicator::RSI)
        ->withCandles($candles)
        ->withParam('period', 14)
        ->execute();
    
    echo "   RSI(14): " . $rsi->getValue() . "\n\n";

    echo "3. Calculate SMA(20):\n";
    $sma = $client
        ->manual(Indicator::SMA)
        ->withCandles($candles)
        ->withParam('period', 20)
        ->execute();
    
    echo "   SMA(20): " . $sma->getValue() . "\n\n";

} catch (\Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException $e) {
    echo "Invalid argument: " . $e->getMessage() . "\n";
} catch (\Tigusigalpa\TAAPI\Exceptions\ApiException $e) {
    echo "API Error: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "=== Example Complete ===\n";
