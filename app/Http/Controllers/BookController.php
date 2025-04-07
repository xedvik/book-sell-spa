<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\ViewService;
use App\Services\ErrorHandlingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    protected BookService $bookService;
    protected ViewService $viewService;
    protected ErrorHandlingService $errorService;

    /**
     * @param BookService $bookService
     * @param ViewService $viewService
     * @param ErrorHandlingService $errorService
     */
    public function __construct(
        BookService $bookService,
        ViewService $viewService,
        ErrorHandlingService $errorService
    ) {
        $this->bookService = $bookService;
        $this->viewService = $viewService;
        $this->errorService = $errorService;
    }

    /**
     * Отображает список книг
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $response = $this->bookService->getFilteredBooks($request);
            $viewData = $this->viewService->prepareBookListData($response);

            return view('books.index', $viewData);
        } catch (\Exception $e) {
            $this->errorService->logException($e, 'books-index');
            $viewData = $this->viewService->prepareBookListData([], $e->getMessage());
            return view('books.index', $viewData);
        }
    }

    /**
     * Отображает детальную информацию о книге
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $response = $this->bookService->getBookDetails($id);
            $viewData = $this->viewService->prepareBookDetailsData($response);

            return view('books.show', $viewData);
        } catch (\Exception $e) {
            $this->errorService->logException($e, 'books-show');
            return $this->errorService->handleExceptionWithRedirect($e, 'books.index');
        }
    }

    /**
     * Покупка книги
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchase(Request $request, $id)
    {
        try {
            $purchaseResult = $this->bookService->processPurchase($request, $id);

            if (isset($purchaseResult['status']) && $purchaseResult['status'] === 'success') {
                $quantity = $request->input('quantity', 1);
                $saleData = $purchaseResult['data'] ?? [];
                $purchaseId = $saleData['sale_id'] ?? 'N/A';

                $message = "Книга успешно куплена! ";
                $message .= "Количество: {$quantity}. ";
                $message .= "Номер заказа: {$purchaseId}";

                return redirect()->route('books.show', $id)
                    ->with('success', $message);
            }


            return redirect()->route('books.show', $id)
                ->with('warning', 'Покупка не была завершена. Пожалуйста, попробуйте позже.');

        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Ошибка при покупке книги в контроллере', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->errorService->logException($e, 'books-purchase');
            return $this->errorService->handleExceptionWithRedirectBack($e);
        }
    }

    /**
     * Отображает список топовых книг
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function topBooks(Request $request)
    {
        try {
            $response = $this->bookService->getFilteredTopBooks($request);
            $viewData = $this->viewService->prepareBookListData($response);

            return view('books.top', $viewData);
        } catch (\Exception $e) {
            $this->errorService->logException($e, 'books-top');
            $viewData = $this->viewService->prepareBookListData([], $e->getMessage());
            return view('books.top', $viewData);
        }
    }
}
