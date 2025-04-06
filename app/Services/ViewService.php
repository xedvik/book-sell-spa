<?php

namespace App\Services;

class ViewService
{
    /**
     * Подготовка данных для представления списка книг
     *
     * @param array $response
     * @param string|null $error
     * @return array
     */
    public function prepareBookListData(array $response, ?string $error = null): array
    {
        $data = [
            'books' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];

        if ($error) {
            $data['error'] = $error;
        }

        return $data;
    }

    /**
     * Подготовка данных для представления детальной информации о книге
     *
     * @param array $response
     * @return array
     */
    public function prepareBookDetailsData(array $response): array
    {
        return [
            'book' => $response['data'] ?? null,
        ];
    }
}
