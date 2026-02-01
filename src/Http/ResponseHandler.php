<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Http;

use Psr\Http\Message\ResponseInterface;
use Tigusigalpa\TAAPI\DTO\BulkResponse;
use Tigusigalpa\TAAPI\DTO\IndicatorResponse;
use Tigusigalpa\TAAPI\Exceptions\ApiException;
use Tigusigalpa\TAAPI\Exceptions\RateLimitException;

class ResponseHandler
{
    public function handleResponse(ResponseInterface $response, bool $isBulk = false): IndicatorResponse|BulkResponse
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        
        if ($statusCode === 429) {
            $this->handleRateLimitError($response, $body);
        }
        
        if ($statusCode >= 400) {
            $this->handleErrorResponse($statusCode, $body);
        }
        
        $data = $this->decodeJson($body);
        
        if ($isBulk) {
            return BulkResponse::fromArray($data);
        }
        
        return IndicatorResponse::fromArray($data);
    }

    private function handleRateLimitError(ResponseInterface $response, string $body): never
    {
        $retryAfter = null;
        
        if ($response->hasHeader('Retry-After')) {
            $retryAfter = (int) $response->getHeaderLine('Retry-After');
        }
        
        $data = $this->decodeJson($body);
        
        throw RateLimitException::fromResponse($data, $retryAfter);
    }

    private function handleErrorResponse(int $statusCode, string $body): never
    {
        $data = $this->decodeJson($body);
        
        throw ApiException::fromResponse($statusCode, $data);
    }

    private function decodeJson(string $json): array
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            
            if (!is_array($data)) {
                throw ApiException::invalidResponse('Response is not a valid JSON array');
            }
            
            return $data;
        } catch (\JsonException $e) {
            throw ApiException::invalidResponse('Failed to decode JSON: ' . $e->getMessage());
        }
    }

    public function validateResponseData(array $data, array $requiredFields = []): void
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw ApiException::invalidResponse("Missing required field: {$field}");
            }
        }
    }
}
