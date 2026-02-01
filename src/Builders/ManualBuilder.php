<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Builders;

use Tigusigalpa\TAAPI\DTO\IndicatorResponse;
use Tigusigalpa\TAAPI\Enums\Indicator;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;
use Tigusigalpa\TAAPI\Http\RequestBuilder;
use Tigusigalpa\TAAPI\Http\ResponseHandler;

class ManualBuilder
{
    private array $candles = [];
    private array $params = [];

    public function __construct(
        private readonly string|Indicator $indicator,
        private readonly RequestBuilder $requestBuilder,
        private readonly ResponseHandler $responseHandler
    ) {
    }

    public function withCandles(array $candles): self
    {
        $this->candles = $candles;
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

    public function execute(): IndicatorResponse
    {
        if (empty($this->candles)) {
            throw InvalidArgumentException::missingParameter('candles');
        }

        $indicatorName = $this->indicator instanceof Indicator 
            ? $this->indicator->value 
            : $this->indicator;

        $payload = array_merge(
            [
                'indicator' => $indicatorName,
                'candles' => $this->candles,
            ],
            $this->params
        );

        $response = $this->requestBuilder->post('/manual', $payload);

        return $this->responseHandler->handleResponse($response, false);
    }
}
