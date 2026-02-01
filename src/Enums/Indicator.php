<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Enums;

enum Indicator: string
{
    case RSI = 'rsi';
    case MACD = 'macd';
    case EMA = 'ema';
    case SMA = 'sma';
    case BBANDS = 'bbands';
    case STOCH = 'stoch';
    case STOCHRSI = 'stochrsi';
    case ATR = 'atr';
    case ADX = 'adx';
    case CCI = 'cci';
    case AROON = 'aroon';
    case MFI = 'mfi';
    case OBV = 'obv';
    case SAR = 'sar';
    case SUPERTREND = 'supertrend';
    case ICHIMOKU = 'ichimoku';
    case VWAP = 'vwap';
    case HMA = 'hma';
    case WMA = 'wma';
    case DEMA = 'dema';
    case TEMA = 'tema';
    case WILLIAMS = 'williams';
    case UO = 'uo';
    case ROC = 'roc';
    case BULL_BEAR_POWER = 'bbp';
    case AO = 'ao';
    case CMF = 'cmf';
    case KELTNER = 'keltner';
    case DONCHIAN = 'donchian';
    case PIVOT = 'pivot';
    case FIBONACCI = 'fibonacci';
    case VOLUME = 'volume';
    case CANDLE = 'candle';

    public static function fromString(string $indicator): self
    {
        return self::from(strtolower($indicator));
    }

    public function toString(): string
    {
        return $this->value;
    }
}
