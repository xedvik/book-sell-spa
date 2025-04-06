<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Конфигурация
    |--------------------------------------------------------------------------
    |
    | Здесь можно указать настройки для работы с API.
    |
    */

    // URL базового API
    'base_url' => env('API_BASE_URL', 'http://host.docker.internal:8000/api'),

    // Таймаут для API-запросов (в секундах)
    'timeout' => env('API_TIMEOUT', 15),

    // Повторные попытки
    'retries' => env('API_RETRIES', 3),

    // Интервал между повторными попытками (в секундах)
    'retry_interval' => env('API_RETRY_INTERVAL', 1),

    // Дополнительные заголовки для API-запросов
    'headers' => [
        'Accept' => 'application/json',
        'X-Client-App' => 'BookSellSPA',
    ],
];
