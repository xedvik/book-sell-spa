<?php

namespace App\Services;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;

class ErrorHandlingService
{
    /**
     *
     *
     * @param Exception $exception
     * @param string $redirectRoute
     * @param array $routeParams
     * @return RedirectResponse|Redirector
     */
    public function handleExceptionWithRedirect(
        Exception $exception,
        string $redirectRoute,
        array $routeParams = []
    ) {
        return redirect()->route($redirectRoute, $routeParams)
            ->with('error', $exception->getMessage());
    }

    /**
     * @param Exception $exception
     * @return RedirectResponse|Redirector
     */
    public function handleExceptionWithRedirectBack(Exception $exception)
    {
        return redirect()->back()
            ->with('error', $exception->getMessage())
            ->withInput();
    }

    /**
     * @param Exception $exception
     * @param string $context
     * @return void
     */
    public function logException(Exception $exception, string $context = 'api')
    {
        $message = "[{$context}] {$exception->getMessage()}";
        $code = $exception->getCode() ?: 500;

        if ($code >= 500) {
            Log::error($message, [
                'exception' => $exception,
                'trace' => $exception->getTraceAsString()
            ]);
        } else {
            Log::warning($message, [
                'exception' => $exception,
                'code' => $code
            ]);
        }
    }
}
