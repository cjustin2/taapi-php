# TAAPI PHP Library - Examples

This directory contains practical examples demonstrating how to use the TAAPI PHP library.

## Files

- **basic_usage.php** - Basic examples of GET (Direct) and POST (Bulk) requests
- **manual_candles.php** - Examples of POST (Manual) requests with custom candle data
- **laravel_usage.php** - Laravel-specific examples including services, controllers, and commands

## Running the Examples

### Prerequisites

1. Install dependencies:
```bash
composer install
```

2. Set your API secret:
```bash
export TAAPI_SECRET=your_api_secret_here
```

Or create a `.env` file in the package root with:
```
TAAPI_SECRET=your_api_secret_here
```

### Run Basic Examples

```bash
php examples/basic_usage.php
```

### Run Manual Candles Example

```bash
php examples/manual_candles.php
```

### Laravel Examples

The `laravel_usage.php` file contains code snippets that should be integrated into your Laravel application:

- Copy the `TradingAnalysisService` to `app/Services/`
- Copy the `TradingController` to `app/Http/Controllers/`
- Copy the `AnalyzeMarketsCommand` to `app/Console/Commands/`

Then you can use them in your Laravel application:

```bash
# Run the artisan command
php artisan markets:analyze

# Or access the controller endpoint
curl http://your-app.test/api/trading/analyze/BTC/USDT
```

## Example Output

When you run the basic examples, you should see output similar to:

```
=== TAAPI PHP Library - Basic Usage Examples ===

1. Simple RSI Request:
   RSI Value: 65.42

2. EMA with Custom Period:
   EMA(50): 42350.25

3. MACD Request:
   MACD: 125.34
   Signal: 118.92
   Histogram: 6.42

4. Bulk Request:
   Total Results: 3
   BTC RSI: 65.42
   BTC EMA(50): 42350.25

=== Examples Complete ===
```

## Notes

- Make sure you have a valid TAAPI API secret
- Be aware of API rate limits
- The examples use real API calls and will consume your API quota
- For testing purposes, consider using the manual candles example which doesn't require live market data
