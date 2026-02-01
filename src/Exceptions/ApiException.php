<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Exceptions;

use Throwable;

class ApiException extends TAAPIException
{
    public function __construct(
        string $message = "",
        public readonly int $statusCode = 0,
        public readonly ?array $responseData = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public static function fromResponse(int $statusCode, array $responseData): self
    {
        $message = $responseData['error'] ?? $responseData['message'] ?? 'Unknown API error';
        
        return new self(
            message: $message,
            statusCode: $statusCode,
            responseData: $responseData
        );
    }

    public static function networkError(string $message, ?Throwable $previous = null): self
    {
        return new self("Network error: {$message}", 0, null, $previous);
    }

    public static function invalidResponse(string $message): self
    {
        return new self("Invalid API response: {$message}");
    }
}
