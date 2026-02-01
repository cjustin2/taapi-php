<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Builders;

use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;

class ConstructBuilder
{
    private array $indicators = [];

    public function __construct(
        private readonly string|Exchange $exchange,
        private readonly string $symbol,
        private readonly string|Interval $interval
    ) {
    }

    public function addIndicator(
        string|Indicator $indicator,
        array $params = []
    ): self {
        $indicatorName = $indicator instanceof Indicator 
            ? $indicator->value 
            : $indicator;

        $indicatorData = ['indicator' => $indicatorName];

        if (!empty($params)) {
            $indicatorData = array_merge($indicatorData, $params);
        }

        $this->indicators[] = $indicatorData;

        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->indicators)) {
            throw InvalidArgumentException::missingParameter('indicators');
        }

        $exchange = $this->exchange instanceof Exchange 
            ? $this->exchange->value 
            : $this->exchange;

        $interval = $this->interval instanceof Interval 
            ? $this->interval->value 
            : $this->interval;

        return [
            'exchange' => $exchange,
            'symbol' => $this->symbol,
            'interval' => $interval,
            'indicators' => $this->indicators,
        ];
    }
}
