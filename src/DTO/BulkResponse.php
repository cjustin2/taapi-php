<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\DTO;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

class BulkResponse implements ArrayAccess, Countable, Iterator, JsonSerializable
{
    private int $position = 0;

    public function __construct(
        private readonly array $responses
    ) {
    }

    public static function fromArray(array $data): self
    {
        $responses = [];
        
        foreach ($data as $item) {
            if (is_array($item)) {
                $responses[] = IndicatorResponse::fromArray($item);
            }
        }
        
        return new self($responses);
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function findById(string $id): ?IndicatorResponse
    {
        foreach ($this->responses as $response) {
            if ($response->id === $id) {
                return $response;
            }
        }
        
        return null;
    }

    public function filterByIndicator(string $indicator): array
    {
        return array_filter(
            $this->responses,
            fn(IndicatorResponse $r) => $r->indicator === $indicator
        );
    }

    public function toArray(): array
    {
        return array_map(fn(IndicatorResponse $r) => $r->toArray(), $this->responses);
    }

    public function count(): int
    {
        return count($this->responses);
    }

    public function current(): IndicatorResponse
    {
        return $this->responses[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->responses[$this->position]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->responses[$offset]);
    }

    public function offsetGet(mixed $offset): ?IndicatorResponse
    {
        return $this->responses[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
