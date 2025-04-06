<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiService
{
    protected string $baseUrl;
    protected PendingRequest $httpClient;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url', 'http://host.docker.internal:8000/api');
        $this->httpClient = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->withHeaders(array_merge(
                [
                    'Content-Type' => 'application/json',
                ],
                config('api.headers', [])
            ))
            ->timeout(config('api.timeout', 15))
            ->retry(
                config('api.retries', 3),
                config('api.retry_interval', 1) * 1000
            );
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    protected function get(string $endpoint, array $params = []): array
    {
        $response = $this->httpClient->get($endpoint, $params);

        return $this->handleResponse($response);
    }
    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    protected function post(string $endpoint, array $data = []): array
    {
        $response = $this->httpClient->post($endpoint, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     * @throws \Exception
     */
    protected function handleResponse($response): array
    {
        $data = $response->json();

        if (!$response->successful()) {
            $message = $data['message'] ?? 'Ошибка при выполнении запроса к API';
            throw new \Exception($message, $response->status());
        }

        return $data;
    }
}
