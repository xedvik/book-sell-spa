<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\ViewService;
use App\Services\ErrorHandlingService;
use Illuminate\Http\Request;

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
            $this->bookService->processPurchase($request, $id);

            return redirect()->route('books.show', $id)
                ->with('success', 'Книга успешно куплена!');
        } catch (\Exception $e) {
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
