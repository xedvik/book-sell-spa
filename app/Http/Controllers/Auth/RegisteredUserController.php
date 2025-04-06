<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\SpaClientApiService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    protected SpaClientApiService $spaClientApiService;

    /**
     * Constructor with dependency injection
     */
    public function __construct(SpaClientApiService $spaClientApiService)
    {
        $this->spaClientApiService = $spaClientApiService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Отправка данных на серверную часть через сервис
        $result = $this->spaClientApiService->register($validated);

        if ($result['success']) {
            // Создаем локального пользователя
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME)->with('success', 'Регистрация успешно завершена!');
        } else {
            if (isset($result['errors']) && is_array($result['errors'])) {
                return back()->withErrors($result['errors'])->withInput();
            }

            return back()->withErrors(['server' => $result['message'] ?? 'Произошла ошибка при регистрации'])->withInput();
        }
    }
}
