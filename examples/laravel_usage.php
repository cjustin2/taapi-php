<?php

declare(strict_types=1);

/**
 * Laravel Usage Examples
 * 
 * These examples show how to use the TAAPI library in a Laravel application.
 * Place these code snippets in your Laravel controllers, services, or commands.
 */

namespace App\Services;

use Tigusigalpa\TAAPI\Laravel\Facades\TAAPI;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;

class TradingAnalysisService
{
    /**
     * Get RSI for a trading pair
     */
    public function getRSI(string $symbol, string $interval = '1h'): float
    {
        $response = TAAPI::exchange(Exchange::BINANCE)
            ->symbol($symbol)
            ->interval($interval)
            ->indicator(Indicator::RSI)
            ->get();
        
        return $response->getValue();
    }

    /**
     * Analyze multiple indicators for a symbol
     */
    public function analyzeSymbol(string $symbol): array
    {
        $results = TAAPI::bulk()
            ->addConstruct(
                TAAPI::construct(Exchange::BINANCE, $symbol, Interval::ONE_HOUR)
                    ->addIndicator(Indicator::RSI, ['id' => 'rsi'])
                    ->addIndicator(Indicator::MACD, ['id' => 'macd'])
                    ->addIndicator(Indicator::EMA, ['period' => 50, 'id' => 'ema_50'])
                    ->addIndicator(Indicator::EMA, ['period' => 200, 'id' => 'ema_200'])
            )
            ->execute();
        
        return [
            'rsi' => $results->findById('rsi')?->getValue(),
            'macd' => $results->findById('macd')?->toArray(),
            'ema_50' => $results->findById('ema_50')?->getValue(),
            'ema_200' => $results->findById('ema_200')?->getValue(),
        ];
    }

    /**
     * Get trading signal based on RSI
     */
    public function getTradingSignal(string $symbol): string
    {
        $rsi = $this->getRSI($symbol);
        
        if ($rsi > 70) {
            return 'overbought';
        } elseif ($rsi < 30) {
            return 'oversold';
        }
        
        return 'neutral';
    }

    /**
     * Compare multiple symbols
     */
    public function compareSymbols(array $symbols): array
    {
        $bulkBuilder = TAAPI::bulk();
        
        foreach ($symbols as $symbol) {
            $bulkBuilder->addConstruct(
                TAAPI::construct(Exchange::BINANCE, $symbol, Interval::ONE_HOUR)
                    ->addIndicator(Indicator::RSI, ['id' => "{$symbol}_rsi"])
            );
        }
        
        $results = $bulkBuilder->execute();
        
        $comparison = [];
        foreach ($symbols as $symbol) {
            $rsi = $results->findById("{$symbol}_rsi");
            if ($rsi) {
                $comparison[$symbol] = [
                    'rsi' => $rsi->getValue(),
                    'signal' => $rsi->getValue() > 70 ? 'overbought' : 
                               ($rsi->getValue() < 30 ? 'oversold' : 'neutral'),
                ];
            }
        }
        
        return $comparison;
    }
}

/**
 * Example Controller Usage
 */
class TradingController
{
    public function __construct(
        private TradingAnalysisService $analysisService
    ) {}

    public function analyze(string $symbol)
    {
        try {
            $analysis = $this->analysisService->analyzeSymbol($symbol);
            
            return response()->json([
                'symbol' => $symbol,
                'analysis' => $analysis,
                'timestamp' => now(),
            ]);
        } catch (\Tigusigalpa\TAAPI\Exceptions\RateLimitException $e) {
            return response()->json([
                'error' => 'Rate limit exceeded',
                'retry_after' => $e->getRetryAfterSeconds(),
            ], 429);
        } catch (\Tigusigalpa\TAAPI\Exceptions\ApiException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->statusCode);
        }
    }
}

/**
 * Example Artisan Command
 */
class AnalyzeMarketsCommand extends \Illuminate\Console\Command
{
    protected $signature = 'markets:analyze';
    protected $description = 'Analyze cryptocurrency markets';

    public function handle(TradingAnalysisService $service)
    {
        $symbols = ['BTC/USDT', 'ETH/USDT', 'BNB/USDT'];
        
        $this->info('Analyzing markets...');
        
        $comparison = $service->compareSymbols($symbols);
        
        $this->table(
            ['Symbol', 'RSI', 'Signal'],
            collect($comparison)->map(fn($data, $symbol) => [
                $symbol,
                number_format($data['rsi'], 2),
                $data['signal'],
            ])->toArray()
        );
        
        return 0;
    }
}
