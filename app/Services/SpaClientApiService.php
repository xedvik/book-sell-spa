<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpaClientApiService
{
    protected string $apiUrl;

    /**
     * Конструктор сервиса
     */
    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL', 'http://localhost:8000/api');
    }

    /**
     * Регистрация нового SPA клиента через API
     *
     * @param array $data Данные клиента
     * @return array{success: bool, data?: array, errors?: array, message?: string}
     */
    public function register(array $data): array
    {
        try {
            $response = Http::post($this->apiUrl . '/spa-clients', [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data'),
                ];
            }

            return [
                'success' => false,
                'errors' => $response->json('errors') ?? [],
                'message' => $response->json('message') ?? 'Ошибка при обращении к API',
            ];
        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации SPA клиента: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Ошибка сервера при регистрации. Пожалуйста, попробуйте позже.',
            ];
        }
    }

    /**
     * Получение информации о клиенте по ID
     *
     * @param int $id ID клиента
     * @return array{success: bool, data?: array, message?: string}
     */
    public function getClientById(int $id): array
    {
        try {
            $response = Http::get($this->apiUrl . '/spa-clients/' . $id);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data'),
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Клиент не найден',
            ];
        } catch (\Exception $e) {
            Log::error('Ошибка при получении данных SPA клиента: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Ошибка сервера при получении данных клиента',
            ];
        }
    }

    /**
     * Обновление данных клиента
     *
     * @param int $id ID клиента
     * @param array $data Данные для обновления
     * @return array{success: bool, data?: array, errors?: array, message?: string}
     */
    public function updateClient(int $id, array $data): array
    {
        try {
            $response = Http::put($this->apiUrl . '/spa-clients/' . $id, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data'),
                ];
            }

            return [
                'success' => false,
                'errors' => $response->json('errors') ?? [],
                'message' => $response->json('message') ?? 'Ошибка при обновлении данных',
            ];
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении данных SPA клиента: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Ошибка сервера при обновлении данных клиента',
            ];
        }
    }
}
