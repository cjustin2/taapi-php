<?php

return [

    /*
    |--------------------------------------------------------------------------
    | TAAPI API Secret
    |--------------------------------------------------------------------------
    |
    | Your taapi.io API secret key. You can obtain this from your taapi.io
    | account dashboard at https://taapi.io/account/
    |
    */

    'api_secret' => env('TAAPI_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum time in seconds to wait for a response from the API.
    | Default is 30 seconds.
    |
    */

    'timeout' => env('TAAPI_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Exchange
    |--------------------------------------------------------------------------
    |
    | The default exchange to use when not specified in requests.
    | Supported: binance, binanceus, binanceusdm, bitfinex, bitget, bitmex,
    | bitstamp, bybit, coinbase, cryptocom, gateio, huobi, kraken, kucoin,
    | mexc, okx, phemex, poloniex
    |
    */

    'default_exchange' => env('TAAPI_DEFAULT_EXCHANGE', 'binance'),

    /*
    |--------------------------------------------------------------------------
    | Default Interval
    |--------------------------------------------------------------------------
    |
    | The default timeframe interval to use when not specified in requests.
    | Supported: 1m, 5m, 15m, 30m, 1h, 2h, 4h, 12h, 1d, 1w
    |
    */

    'default_interval' => env('TAAPI_DEFAULT_INTERVAL', '1h'),

];
