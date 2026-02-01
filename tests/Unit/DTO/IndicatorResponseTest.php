<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Tigusigalpa\TAAPI\DTO\IndicatorResponse;

class IndicatorResponseTest extends TestCase
{
    public function test_from_array(): void
    {
        $data = [
            'indicator' => 'rsi',
            'value' => 65.5,
            'id' => 'test_id',
        ];

        $response = IndicatorResponse::fromArray($data);

        $this->assertEquals('rsi', $response->indicator);
        $this->assertEquals('test_id', $response->id);
        $this->assertEquals(65.5, $response->getValue());
    }

    public function test_get_value(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 70.0]);
        $this->assertEquals(70.0, $response->getValue());
    }

    public function test_get_method(): void
    {
        $response = new IndicatorResponse('macd', [
            'valueMACD' => 1.5,
            'valueMACDSignal' => 1.2,
        ]);

        $this->assertEquals(1.5, $response->get('valueMACD'));
        $this->assertEquals(1.2, $response->get('valueMACDSignal'));
        $this->assertNull($response->get('nonexistent'));
        $this->assertEquals('default', $response->get('nonexistent', 'default'));
    }

    public function test_has_method(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 65.5]);
        $this->assertTrue($response->has('value'));
        $this->assertFalse($response->has('nonexistent'));
    }

    public function test_array_access(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 65.5]);
        $this->assertEquals(65.5, $response['value']);
        $this->assertTrue(isset($response['value']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function test_magic_get(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 65.5]);
        $this->assertEquals(65.5, $response->value);
    }

    public function test_to_array(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 65.5], 'test_id');
        $array = $response->toArray();

        $this->assertEquals('rsi', $array['indicator']);
        $this->assertEquals(65.5, $array['value']);
        $this->assertEquals('test_id', $array['id']);
    }

    public function test_json_serialize(): void
    {
        $response = new IndicatorResponse('rsi', ['value' => 65.5]);
        $json = json_encode($response);
        $decoded = json_decode($json, true);

        $this->assertEquals('rsi', $decoded['indicator']);
        $this->assertEquals(65.5, $decoded['value']);
    }
}
