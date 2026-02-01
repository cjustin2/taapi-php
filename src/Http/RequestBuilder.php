<?php

declare(strict_types=1);

namespace Tigusigalpa\TAAPI\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Tigusigalpa\TAAPI\Exceptions\ApiException;

class RequestBuilder
{
    private const BASE_URL = 'https://api.taapi.io';
    
    private Client $client;
    private string $apiSecret;
    private array $headers = [];
    private int $timeout = 30;

    public function __construct(string $apiSecret, ?Client $client = null)
    {
        $this->apiSecret = $apiSecret;
        $this->client = $client ?? new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function get(string $endpoint, array $params = []): ResponseInterface
    {
        try {
            $params['secret'] = $this->apiSecret;
            
            return $this->client->get($endpoint, [
                'query' => $params,
                'headers' => $this->headers,
                'timeout' => $this->timeout,
            ]);
        } catch (GuzzleException $e) {
            throw ApiException::networkError($e->getMessage(), $e);
        }
    }

    public function post(string $endpoint, array $data = []): ResponseInterface
    {
        try {
            $data['secret'] = $this->apiSecret;
            
            return $this->client->post($endpoint, [
                'json' => $data,
                'headers' => $this->headers,
                'timeout' => $this->timeout,
            ]);
        } catch (GuzzleException $e) {
            throw ApiException::networkError($e->getMessage(), $e);
        }
    }

    public function buildGetUrl(string $indicator, array $params): string
    {
        $params['secret'] = $this->apiSecret;
        $query = http_build_query($params);
        
        return self::BASE_URL . '/' . $indicator . '?' . $query;
    }
}
