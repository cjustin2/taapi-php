<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Builders;

use Tigusigalpa\TAAPI\DTO\IndicatorResponse;
use Tigusigalpa\TAAPI\Enums\Exchange;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Enums\Interval;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;
use Tigusigalpa\TAAPI\Http\RequestBuilder;
use Tigusigalpa\TAAPI\Http\ResponseHandler;

class DirectBuilder
{
    private ?string $exchange = null;
    private ?string $symbol = null;
    private ?string $interval = null;
    private ?string $indicator = null;
    private array $params = [];

    public function __construct(
        private readonly RequestBuilder $requestBuilder,
        private readonly ResponseHandler $responseHandler
    ) {
    }

    public function exchange(string|Exchange $exchange): self
    {
        $this->exchange = $exchange instanceof Exchange 
            ? $exchange->value 
            : $exchange;
        return $this;
    }

    public function symbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    public function interval(string|Interval $interval): self
    {
        $this->interval = $interval instanceof Interval 
            ? $interval->value 
            : $interval;
        return $this;
    }

    public function indicator(string|Indicator $indicator): self
    {
        $this->indicator = $indicator instanceof Indicator 
            ? $indicator->value 
            : $indicator;
        return $this;
    }

    public function withParams(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function withParam(string $key, mixed $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function backtrack(int $backtrack): self
    {
        $this->params['backtrack'] = $backtrack;
        return $this;
    }

    public function backtracks(int $backtracks): self
    {
        $this->params['backtracks'] = $backtracks;
        return $this;
    }

    public function get(): IndicatorResponse
    {
        $this->validate();

        $params = array_merge([
            'exchange' => $this->exchange,
            'symbol' => $this->symbol,
            'interval' => $this->interval,
        ], $this->params);

        $response = $this->requestBuilder->get('/' . $this->indicator, $params);

        return $this->responseHandler->handleResponse($response, false);
    }

    private function validate(): void
    {
        if ($this->exchange === null) {
            throw InvalidArgumentException::missingParameter('exchange');
        }

        if ($this->symbol === null) {
            throw InvalidArgumentException::missingParameter('symbol');
        }

        if ($this->interval === null) {
            throw InvalidArgumentException::missingParameter('interval');
        }

        if ($this->indicator === null) {
            throw InvalidArgumentException::missingParameter('indicator');
        }
    }
}
