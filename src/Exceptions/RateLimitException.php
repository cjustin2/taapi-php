<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Exceptions;

class RateLimitException extends ApiException
{
    public function __construct(
        string $message = "API rate limit exceeded",
        public readonly ?int $retryAfter = null,
        public readonly ?array $responseData = null
    ) {
        parent::__construct($message, 429, $responseData);
    }

    public static function fromResponse(array $responseData, ?int $retryAfter = null): self
    {
        $message = $responseData['error'] ?? 'Rate limit exceeded';
        
        return new self(
            message: $message,
            retryAfter: $retryAfter,
            responseData: $responseData
        );
    }

    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfter;
    }
}
