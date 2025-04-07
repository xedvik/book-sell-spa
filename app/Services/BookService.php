<?php

namespace App\Services;

use App\Events\BookPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            $userId = auth()->id() ?? $request->input('user_id', 1);
            $quantity = $request->input('quantity', 1);
            $bookDetails = $this->getBookDetails($bookId);
            $purchaseResult = $this->purchaseBook($bookId, $userId, $quantity);


            if (isset($purchaseResult['status']) && $purchaseResult['status'] === 'success') {
                $user = Auth::user();

                $saleData = $purchaseResult['data'] ?? [];
                $saleId = $saleData['sale_id'] ?? rand(1000, 9999);
                $bookTitle = $saleData['book']['title'] ?? $bookDetails['data']['title'] ?? 'Неизвестная книга';
                $bookPrice = $saleData['book']['price'] ?? $bookDetails['data']['price'] ?? 0;
                $totalPrice = $saleData['total_price'] ?? ($bookPrice * $quantity);

                $purchaseData = [
                    'id' => $saleId,
                    'book_id' => $bookId,
                    'book_title' => $bookTitle,
                    'user_id' => $userId,
                    'user_name' => $user ? $user->name : 'Гость',
                    'quantity' => $quantity,
                    'price' => $bookPrice,
                    'total_price' => $totalPrice,
                    'purchased_at' => now()->format('Y-m-d H:i:s')
                ];
                try {
                    event(new BookPurchased($purchaseData));
                } catch (\Exception $e) {
                    Log::error('Ошибка при отправке события BookPurchased', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::warning('Покупка не была успешной, событие не отправлено', [
                    'result' => $purchaseResult
                ]);
            }
            return $purchaseResult;
        } catch (\Exception $e) {
            Log::error('Ошибка в процессе покупки книги', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
