<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\DTO;

use ArrayAccess;
use JsonSerializable;

class IndicatorResponse implements ArrayAccess, JsonSerializable
{
    public function __construct(
        public readonly string $indicator,
        public readonly array $data,
        public readonly ?string $id = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        $indicator = $data['indicator'] ?? 'unknown';
        $id = $data['id'] ?? null;
        
        unset($data['indicator'], $data['id']);
        
        return new self($indicator, $data, $id);
    }

    public function getValue(): mixed
    {
        return $this->data['value'] ?? $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function toArray(): array
    {
        $result = ['indicator' => $this->indicator] + $this->data;
        
        if ($this->id !== null) {
            $result['id'] = $this->id;
        }
        
        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
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

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }
}
