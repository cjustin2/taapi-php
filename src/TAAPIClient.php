<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI;

use GuzzleHttp\Client;
use Tigusigalpa\TAAPI\Builders\BulkBuilder;
use Tigusigalpa\TAAPI\Builders\ConstructBuilder;
use Tigusigalpa\TAAPI\Builders\DirectBuilder;
use Tigusigalpa\TAAPI\Builders\ManualBuilder;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\Http\RequestBuilder;
use Tigusigalpa\TAAPI\Http\ResponseHandler;

/**
 * TAAPI Client - Modern PHP library for taapi.io API
 * 
 * @author Igor Sazonov <sovletig@gmail.com>
 * @link https://github.com/tigusigalpa/taapi-php
 * @link https://taapi.io/documentation/
 */
class TAAPIClient
{
    private RequestBuilder $requestBuilder;
    private ResponseHandler $responseHandler;

    /**
     * Initialize TAAPI Client
     * 
     * @param string $apiSecret Your taapi.io API secret key
     * @param Client|null $httpClient Optional custom Guzzle HTTP client
     */
    public function __construct(
        private readonly string $apiSecret,
        ?Client $httpClient = null
    ) {
        $this->requestBuilder = new RequestBuilder($this->apiSecret, $httpClient);
        $this->responseHandler = new ResponseHandler();
    }

    /**
     * Start building a GET (Direct) request
     * 
     * @return DirectBuilder
     * 
     * @example
     * ```php
     * $client->exchange('binance')
     *        ->symbol('BTC/USDT')
     *        ->interval('1h')
     *        ->indicator('rsi')
     *        ->get();
     * ```
     */
    public function exchange(string|Exchange $exchange): DirectBuilder
    {
        return (new DirectBuilder($this->requestBuilder, $this->responseHandler))
            ->exchange($exchange);
    }

    /**
     * Start building a GET (Direct) request with symbol
     * 
     * @param string $symbol Trading pair symbol (e.g., 'BTC/USDT')
     * @return DirectBuilder
     */
    public function symbol(string $symbol): DirectBuilder
    {
        return (new DirectBuilder($this->requestBuilder, $this->responseHandler))
            ->symbol($symbol);
    }

    /**
     * Start building a GET (Direct) request with interval
     * 
     * @param string|Interval $interval Timeframe interval
     * @return DirectBuilder
     */
    public function interval(string|Interval $interval): DirectBuilder
    {
        return (new DirectBuilder($this->requestBuilder, $this->responseHandler))
            ->interval($interval);
    }

    /**
     * Start building a GET (Direct) request with indicator
     * 
     * @param string|Indicator $indicator Technical indicator name
     * @return DirectBuilder
     */
    public function indicator(string|Indicator $indicator): DirectBuilder
    {
        return (new DirectBuilder($this->requestBuilder, $this->responseHandler))
            ->indicator($indicator);
    }

    /**
     * Create a new Direct request builder
     * 
     * @return DirectBuilder
     */
    public function direct(): DirectBuilder
    {
        return new DirectBuilder($this->requestBuilder, $this->responseHandler);
    }

    /**
     * Start building a POST (Bulk) request
     * 
     * @return BulkBuilder
     * 
     * @example
     * ```php
     * $client->bulk()
     *        ->addConstruct(
     *            $client->construct('binance', 'BTC/USDT', '1h')
     *                   ->addIndicator('rsi')
     *        )
     *        ->execute();
     * ```
     */
    public function bulk(): BulkBuilder
    {
        return new BulkBuilder($this->requestBuilder, $this->responseHandler);
    }

    /**
     * Create a construct for bulk requests
     * 
     * @param string|Exchange $exchange Exchange name
     * @param string $symbol Trading pair symbol
     * @param string|Interval $interval Timeframe interval
     * @return ConstructBuilder
     * 
     * @example
     * ```php
     * $construct = $client->construct('binance', 'BTC/USDT', '1h')
     *                     ->addIndicator('rsi', ['period' => 14])
     *                     ->addIndicator('macd');
     * ```
     */
    public function construct(
        string|Exchange $exchange,
        string $symbol,
        string|Interval $interval
    ): ConstructBuilder {
        return new ConstructBuilder($exchange, $symbol, $interval);
    }

    /**
     * Start building a POST (Manual) request with custom candle data
     * 
     * @param string|Indicator $indicator Technical indicator name
     * @return ManualBuilder
     * 
     * @example
     * ```php
     * $client->manual('ema')
     *        ->withCandles($candleData)
     *        ->withParams(['period' => 50])
     *        ->execute();
     * ```
     */
    public function manual(string|Indicator $indicator): ManualBuilder
    {
        return new ManualBuilder($indicator, $this->requestBuilder, $this->responseHandler);
    }

    /**
     * Get the API secret key
     * 
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * Get the request builder instance
     * 
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return $this->requestBuilder;
    }

    /**
     * Get the response handler instance
     * 
     * @return ResponseHandler
     */
    public function getResponseHandler(): ResponseHandler
    {
        return $this->responseHandler;
    }

    /**
     * Set request timeout in seconds
     * 
     * @param int $timeout Timeout in seconds
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->requestBuilder->setTimeout($timeout);
        return $this;
    }
}
