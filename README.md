# TAAPI PHP Library

![TAAPI PHP](https://github.com/user-attachments/assets/69dae5e6-56bc-4973-9ea2-920f9eaa7d75)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)

Modern, object-oriented PHP/Laravel library for the [taapi.io](https://taapi.io/) technical analysis API.

## Features

- ✨ **Modern PHP 8.1+** with strict types and enums
- 🔄 **Fluent Interface** for intuitive request building
- 📦 **Full API Coverage** - GET (Direct), POST (Bulk), and POST (Manual) requests
- 🎯 **Type-Safe** - Strongly typed responses with DTOs
- 🚀 **Laravel Integration** - Service Provider, Facade, and configuration
- 🛡️ **Error Handling** - Custom exceptions for different error types
- ✅ **Well Tested** - Comprehensive unit and integration tests
- 📖 **Fully Documented** - Complete PHPDoc documentation

## Installation

Install via Composer:

```bash
composer require tigusigalpa/taapi-php
```

## Configuration

### Standard PHP

```php
use Tigusigalpa\TAAPI\TAAPIClient;

$taapi = new TAAPIClient('YOUR_API_SECRET');
```

### Laravel

1. **Publish the configuration file** (optional):

```bash
php artisan vendor:publish --tag=taapi-config
```

2. **Add your API secret to `.env`**:

```env
TAAPI_SECRET=your_api_secret_here
TAAPI_TIMEOUT=30
TAAPI_DEFAULT_EXCHANGE=binance
TAAPI_DEFAULT_INTERVAL=1h
```

3. **Use the Facade**:

```php
use Tigusigalpa\TAAPI\Laravel\Facades\TAAPI;

// Or with alias
use TAAPI;
```

## Usage

### GET (Direct) Requests

Get a single indicator value for a specific exchange, symbol, and interval.

#### Basic Example

```php
use Tigusigalpa\TAAPI\Laravel\Facades\TAAPI;

$rsi = TAAPI::exchange('binance')
    ->symbol('BTC/USDT')
    ->interval('1h')
    ->indicator('rsi')
    ->get();

echo "RSI Value: " . $rsi->getValue();
```

#### Using Enums

```php
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;

$macd = TAAPI::exchange(Exchange::BINANCE)
    ->symbol('ETH/USDT')
    ->interval(Interval::FOUR_HOURS)
    ->indicator(Indicator::MACD)
    ->get();

echo "MACD: " . $macd->get('valueMACD');
echo "Signal: " . $macd->get('valueMACDSignal');
echo "Histogram: " . $macd->get('valueMACDHist');
```

#### With Additional Parameters

```php
$ema = TAAPI::exchange('binance')
    ->symbol('BTC/USDT')
    ->interval('1h')
    ->indicator('ema')
    ->withParams(['period' => 50])
    ->get();

echo "EMA(50): " . $ema->getValue();
```

#### With Backtrack

```php
// Get historical data
$rsi = TAAPI::exchange('binance')
    ->symbol('BTC/USDT')
    ->interval('1h')
    ->indicator('rsi')
    ->backtrack(5)
    ->get();

// Get multiple historical values
$rsi = TAAPI::exchange('binance')
    ->symbol('BTC/USDT')
    ->interval('1h')
    ->indicator('rsi')
    ->backtracks(10)
    ->get();
```

### POST (Bulk) Requests

Execute multiple indicator requests in a single API call for better performance.

#### Basic Bulk Request

```php
$results = TAAPI::bulk()
    ->addConstruct(
        TAAPI::construct('binance', 'BTC/USDT', '1h')
            ->addIndicator('rsi', ['id' => 'btc_rsi'])
            ->addIndicator('macd', ['id' => 'btc_macd'])
    )
    ->addConstruct(
        TAAPI::construct('binance', 'ETH/USDT', '4h')
            ->addIndicator('sma', ['period' => 200, 'id' => 'eth_sma'])
    )
    ->execute();

// Access results by ID
$btcRsi = $results->findById('btc_rsi');
echo "BTC RSI: " . $btcRsi->getValue();

// Iterate through all results
foreach ($results as $result) {
    echo "{$result->indicator}: {$result->getValue()}\n";
}
```

#### Advanced Bulk Request

```php
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Interval;

$results = TAAPI::bulk()
    ->addConstruct(
        TAAPI::construct(Exchange::BINANCE, 'BTC/USDT', Interval::ONE_HOUR)
            ->addIndicator('rsi', ['period' => 14, 'id' => 'rsi_14'])
            ->addIndicator('rsi', ['period' => 21, 'id' => 'rsi_21'])
            ->addIndicator('ema', ['period' => 50, 'id' => 'ema_50'])
            ->addIndicator('ema', ['period' => 200, 'id' => 'ema_200'])
    )
    ->addConstruct(
        TAAPI::construct(Exchange::COINBASE, 'ETH/USD', Interval::FOUR_HOURS)
            ->addIndicator('bbands', ['id' => 'eth_bb'])
            ->addIndicator('stoch', ['id' => 'eth_stoch'])
    )
    ->execute();

// Filter by indicator type
$rsiResults = $results->filterByIndicator('rsi');
foreach ($rsiResults as $rsi) {
    echo "RSI ({$rsi->id}): {$rsi->getValue()}\n";
}
```

### POST (Manual) Requests

Calculate indicators using your own candle data.

#### Basic Manual Request

```php
$candles = [
    [1609459200, 28923.63, 28923.63, 28923.63, 28923.63, 0.00000000],
    [1609462800, 29083.37, 29188.78, 28963.64, 29103.37, 1107.05626800],
    [1609466400, 29103.38, 29152.98, 28980.01, 29050.00, 978.58108600],
    // ... more candles
];

$ema = TAAPI::manual('ema')
    ->withCandles($candles)
    ->withParams(['period' => 50])
    ->execute();

echo "EMA(50): " . $ema->getValue();
```

#### Using Indicator Enum

```php
use Tigusigalpa\TAAPI\Enums\Indicator;

$rsi = TAAPI::manual(Indicator::RSI)
    ->withCandles($candles)
    ->withParam('period', 14)
    ->execute();

echo "RSI: " . $rsi->getValue();
```

## Response Handling

### IndicatorResponse

All single indicator requests return an `IndicatorResponse` object:

```php
$response = TAAPI::exchange('binance')
    ->symbol('BTC/USDT')
    ->interval('1h')
    ->indicator('rsi')
    ->get();

// Get the main value
$value = $response->getValue();

// Access specific fields
$value = $response->get('value');
$value = $response['value'];
$value = $response->value;

// Check if field exists
if ($response->has('value')) {
    // ...
}

// Convert to array
$array = $response->toArray();

// JSON serialization
$json = json_encode($response);
```

### BulkResponse

Bulk requests return a `BulkResponse` object:

```php
$results = TAAPI::bulk()
    ->addConstruct(/* ... */)
    ->execute();

// Count results
$count = count($results);

// Find by ID
$result = $results->findById('my_indicator');

// Filter by indicator
$rsiResults = $results->filterByIndicator('rsi');

// Iterate
foreach ($results as $result) {
    echo $result->indicator . ': ' . $result->getValue();
}

// Array access
$first = $results[0];

// Convert to array
$array = $results->toArray();
```

## Error Handling

The library provides custom exceptions for different error scenarios:

```php
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;
use Tigusigalpa\TAAPI\Exceptions\ApiException;
use Tigusigalpa\TAAPI\Exceptions\RateLimitException;

try {
    $result = TAAPI::exchange('binance')
        ->symbol('BTC/USDT')
        ->interval('1h')
        ->indicator('rsi')
        ->get();
} catch (InvalidArgumentException $e) {
    // Handle invalid parameters
    echo "Invalid argument: " . $e->getMessage();
} catch (RateLimitException $e) {
    // Handle rate limiting
    echo "Rate limit exceeded. Retry after: " . $e->getRetryAfterSeconds() . " seconds";
} catch (ApiException $e) {
    // Handle general API errors
    echo "API Error [{$e->statusCode}]: " . $e->getMessage();
    print_r($e->responseData);
}
```

## Available Enums

### Exchanges

```php
use Tigusigalpa\TAAPI\Enums\Exchange;

Exchange::BINANCE
Exchange::BINANCEUS
Exchange::BINANCEUSDM
Exchange::BITFINEX
Exchange::BITGET
Exchange::BITMEX
Exchange::BITSTAMP
Exchange::BYBIT
Exchange::COINBASE
Exchange::CRYPTOCOM
Exchange::GATEIO
Exchange::HUOBI
Exchange::KRAKEN
Exchange::KUCOIN
Exchange::MEXC
Exchange::OKX
Exchange::PHEMEX
Exchange::POLONIEX
```

### Intervals

```php
use Tigusigalpa\TAAPI\Enums\Interval;

Interval::ONE_MINUTE      // '1m'
Interval::FIVE_MINUTES    // '5m'
Interval::FIFTEEN_MINUTES // '15m'
Interval::THIRTY_MINUTES  // '30m'
Interval::ONE_HOUR        // '1h'
Interval::TWO_HOURS       // '2h'
Interval::FOUR_HOURS      // '4h'
Interval::TWELVE_HOURS    // '12h'
Interval::ONE_DAY         // '1d'
Interval::ONE_WEEK        // '1w'
```

### Indicators

```php
use Tigusigalpa\TAAPI\Enums\Indicator;

Indicator::RSI
Indicator::MACD
Indicator::EMA
Indicator::SMA
Indicator::BBANDS
Indicator::STOCH
Indicator::STOCHRSI
Indicator::ATR
Indicator::ADX
Indicator::CCI
// ... and many more
```

See the [Indicator enum](src/Enums/Indicator.php) for the complete list.

## Advanced Usage

### Custom Timeout

```php
// Standard PHP
$taapi = new TAAPIClient('YOUR_API_SECRET');
$taapi->setTimeout(60);

// Laravel
TAAPI::setTimeout(60);
```

### Dependency Injection (Laravel)

```php
use Tigusigalpa\TAAPI\TAAPIClient;

class TradingService
{
    public function __construct(
        private TAAPIClient $taapi
    ) {}
    
    public function analyzeMarket(string $symbol): array
    {
        $rsi = $this->taapi
            ->exchange('binance')
            ->symbol($symbol)
            ->interval('1h')
            ->indicator('rsi')
            ->get();
            
        return [
            'rsi' => $rsi->getValue(),
            'signal' => $rsi->getValue() > 70 ? 'overbought' : 'oversold',
        ];
    }
}
```

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test -- --coverage
```

## Requirements

- PHP 8.1 or higher
- Guzzle HTTP client 7.5+
- Laravel 10.0+ or 11.0+ (for Laravel integration)

## Links

- [taapi.io Official Website](https://taapi.io/)
- [taapi.io Documentation](https://taapi.io/documentation/)
- [taapi.io API Reference](https://taapi.io/documentation/integration/)
- [GitHub Repository](https://github.com/tigusigalpa/taapi-php)

## License

This library is open-sourced software licensed under the [MIT license](LICENSE).

## Author

**Igor Sazonov**
- Email: sovletig@gmail.com
- GitHub: [@tigusigalpa](https://github.com/tigusigalpa)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

If you encounter any issues or have questions, please [open an issue](https://github.com/tigusigalpa/taapi-php/issues) on GitHub.
