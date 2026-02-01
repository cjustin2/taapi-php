<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Builders;

use Tigusigalpa\TAAPI\DTO\BulkResponse;
use Tigusigalpa\TAAPI\Exceptions\InvalidArgumentException;
use Tigusigalpa\TAAPI\Http\RequestBuilder;
use Tigusigalpa\TAAPI\Http\ResponseHandler;

class BulkBuilder
{
    private array $constructs = [];

    public function __construct(
        private readonly RequestBuilder $requestBuilder,
        private readonly ResponseHandler $responseHandler
    ) {
    }

    public function addConstruct(ConstructBuilder $construct): self
    {
        $this->constructs[] = $construct->toArray();
        return $this;
    }

    public function execute(): BulkResponse
    {
        if (empty($this->constructs)) {
            throw InvalidArgumentException::missingParameter('constructs');
        }

        $payload = ['construct' => $this->constructs];

        $response = $this->requestBuilder->post('/bulk', $payload);

        return $this->responseHandler->handleResponse($response, true);
    }

    public function getConstructs(): array
    {
        return $this->constructs;
    }
}
