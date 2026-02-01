<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Enums;

enum Exchange: string
{
    case BINANCE = 'binance';
    case BINANCEUS = 'binanceus';
    case BINANCEUSDM = 'binanceusdm';
    case BITFINEX = 'bitfinex';
    case BITGET = 'bitget';
    case BITMEX = 'bitmex';
    case BITSTAMP = 'bitstamp';
    case BYBIT = 'bybit';
    case COINBASE = 'coinbase';
    case CRYPTOCOM = 'cryptocom';
    case GATEIO = 'gateio';
    case HUOBI = 'huobi';
    case KRAKEN = 'kraken';
    case KUCOIN = 'kucoin';
    case MEXC = 'mexc';
    case OKX = 'okx';
    case PHEMEX = 'phemex';
    case POLONIEX = 'poloniex';

    public static function fromString(string $exchange): self
    {
        return self::from(strtolower($exchange));
    }

    public function toString(): string
    {
        return $this->value;
    }
}
