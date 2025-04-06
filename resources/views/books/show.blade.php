<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Информация о книге') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 m-6 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-6 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if(!isset($book))
                    <div class="p-6">
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400">Книга не найдена.</p>
                            <a href="{{ route('books.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150">
                                Вернуться к списку книг
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                        <!-- Изображение книги -->
                        <div class="md:col-span-1">
                            <div class="h-96 overflow-hidden bg-gray-200 dark:bg-gray-700 rounded-lg">
                                @if(isset($book['cover_url']) && $book['cover_url'])
                                    <img src="{{ $book['cover_url'] }}" alt="{{ $book['title'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Информация о книге -->
                        <div class="md:col-span-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $book['title'] }}</h1>

                            <!-- Авторы -->
                            <div class="mb-4">
                                @if(isset($book['authors']) && !empty($book['authors']))
                                    @if(count($book['authors']) == 1)
                                        <p class="text-lg text-gray-700 dark:text-gray-300">
                                            <span class="font-semibold">Автор:</span>
                                            {{ $book['authors'][0]['full_name'] }}

                                            @if(isset($book['authors'][0]['rank']))
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $book['authors'][0]['rank'] >= 85 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    Рейтинг: {{ $book['authors'][0]['rank'] }}
                                                </span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-lg text-gray-700 dark:text-gray-300">
                                            <span class="font-semibold">Авторы:</span>
                                        </p>
                                        <ul class="mt-1 list-disc list-inside">
                                            @foreach($book['authors'] as $author)
                                                <li class="text-gray-700 dark:text-gray-300">
                                                    {{ $author['full_name'] }}
                                                    @if(isset($author['rank']))
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $author['rank'] >= 85 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            Рейтинг: {{ $author['rank'] }}
                                                        </span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @else
                                    <p class="text-lg text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold">Автор:</span> Неизвестен
                                    </p>
                                @endif
                            </div>

                            <!-- Информация о цене и наличии -->
                            <div class="flex flex-wrap justify-between items-center p-4 mb-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="mb-2 md:mb-0">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($book['price'], 2, '.', ' ') }} ₽
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        @if($book['quantity'] > 10)
                                            <span class="text-green-600 dark:text-green-400">В наличии</span>
                                        @elseif($book['quantity'] > 0)
                                            <span class="text-yellow-600 dark:text-yellow-400">Осталось мало: {{ $book['quantity'] }} шт.</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">Нет в наличии</span>
                                        @endif
                                    </div>
                                </div>

                                @if($book['quantity'] > 0)
                                    <div>
                                        <form action="{{ route('books.purchase', $book['id']) }}" method="POST">
                                            @csrf
                                            <div class="flex items-center mb-2">
                                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Количество:</label>
                                                <select name="quantity" id="quantity" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                                                    @for ($i = 1; $i <= min(5, $book['quantity']); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Купить
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <!-- Дополнительная информация -->
                            <div class="mb-4">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Описание</h2>
                                <div class="text-gray-600 dark:text-gray-400">
                                    {{ $book['description'] ?? 'Описание отсутствует.' }}
                                </div>
                            </div>

                            <!-- Дополнительные характеристики -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-1">Детали</h3>
                                    <ul class="text-sm text-gray-600 dark:text-gray-400">
                                        @if(isset($book['created_at']))
                                            <li class="mb-1">Добавлена: {{ \Carbon\Carbon::parse($book['created_at'])->format('d.m.Y') }}</li>
                                        @endif
                                        @if(isset($book['isbn']))
                                            <li class="mb-1">ISBN: {{ $book['isbn'] }}</li>
                                        @endif
                                        @if(isset($book['publication_year']))
                                            <li class="mb-1">Год издания: {{ $book['publication_year'] }}</li>
                                        @endif
                                    </ul>
                                </div>

                                <div>
                                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-1">Статистика</h3>
                                    <ul class="text-sm text-gray-600 dark:text-gray-400">
                                        @if(isset($book['sells_count']))
                                            <li class="mb-1">Всего продаж: {{ $book['sells_count'] }}</li>
                                        @endif
                                        @if(isset($book['sells_today']))
                                            <li class="mb-1">Продаж сегодня: {{ $book['sells_today'] }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Кнопки навигации -->
                            <div class="mt-6 flex space-x-2">
                                <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    К списку книг
                                </a>

                                <a href="{{ route('books.top') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Топ книг
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
