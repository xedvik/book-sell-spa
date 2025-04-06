<?php

namespace App\Services;

use Illuminate\Http\Request;

class BookService extends ApiService
{
    /**
     * Получить список доступных книг
     *
     * @param array $filters
     * @return array
     */
    public function getAvailableBooks(array $filters = []): array
    {
        return $this->get('/books', $filters);
    }

    /**
     * Получить детальную информацию о книге
     *
     * @param int $id
     * @return array
     */
    public function getBookDetails(int $id): array
    {
        return $this->get("/books/{$id}");
    }

    /**
     * Купить книгу
     *
     * @param int $bookId
     * @param int $userId
     * @param int $quantity
     * @return array
     */
    public function purchaseBook(int $bookId, int $userId, int $quantity = 1): array
    {
        return $this->post("/books/{$bookId}/purchase", [
            'user_id' => $userId,
            'quantity' => $quantity
        ]);
    }

    /**
     * Получить список топовых книг
     *
     * @param array $filters
     * @return array
     */
    public function getTopBooks(array $filters = []): array
    {
        return $this->get('/books/top', $filters);
    }

    /**
     * Получить отфильтрованный список книг
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getFilteredBooks(Request $request): array
    {
        $filters = $request->only([
            'sort_by',
            'sort_direction',
            'title',
            'min_price',
            'max_price',
            'min_quantity',
            'with_author_avatar'
        ]);

        return $this->getAvailableBooks($filters);
    }

    /**
     * Получить отфильтрованный список топовых книг
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getFilteredTopBooks(Request $request): array
    {
        $filters = $request->only([
            'sort_by',
            'sort_direction',
            'title',
            'min_price',
            'max_price',
            'min_quantity',
            'min_author_rank',
            'min_today_sales'
        ]);

        return $this->getTopBooks($filters);
    }

    /**
     * Выполнить покупку книги
     *
     * @param Request $request
     * @param int $bookId
     * @return array
     * @throws \Exception
     */
    public function processPurchase(Request $request, int $bookId): array
    {
        $userId = auth()->id() ?? $request->input('user_id', 1);
        $quantity = $request->input('quantity', 1);

        return $this->purchaseBook($bookId, $userId, $quantity);
    }
}
