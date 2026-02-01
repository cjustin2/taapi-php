<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Tigusigalpa\TAAPI\Builders\BulkBuilder;
use Tigusigalpa\TAAPI\Builders\ConstructBuilder;
use Tigusigalpa\TAAPI\Builders\DirectBuilder;
use Tigusigalpa\TAAPI\Builders\ManualBuilder;
use Tigusigalpa\TAAPI\DTO\BulkResponse;
use Tigusigalpa\TAAPI\DTO\IndicatorResponse;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\TAAPIClient;

/**
 * @method static DirectBuilder exchange(string|Exchange $exchange)
 * @method static DirectBuilder symbol(string $symbol)
 * @method static DirectBuilder interval(string|Interval $interval)
 * @method static DirectBuilder indicator(string|Indicator $indicator)
 * @method static DirectBuilder direct()
 * @method static BulkBuilder bulk()
 * @method static ConstructBuilder construct(string|Exchange $exchange, string $symbol, string|Interval $interval)
 * @method static ManualBuilder manual(string|Indicator $indicator)
 * @method static TAAPIClient setTimeout(int $timeout)
 * @method static string getApiSecret()
 *
 * @see \Tigusigalpa\TAAPI\TAAPIClient
 */
class TAAPI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return TAAPIClient::class;
    }
}
