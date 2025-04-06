<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Топ книг') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <p>{{ $error }}</p>
                    </div>
                @endif

                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <p class="text-blue-800 dark:text-blue-200">
                        Здесь представлены книги авторов с рейтингом выше 75 или книги с высокими продажами за сегодня (более 3).
                    </p>
                </div>

                <!-- Фильтры -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Фильтры</h3>
                    <form action="{{ route('books.top') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Название</label>
                            <input type="text" name="title" id="title" value="{{ request('title') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Мин. цена</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Макс. цена</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="min_author_rank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Мин. рейтинг автора</label>
                            <input type="number" name="min_author_rank" id="min_author_rank" value="{{ request('min_author_rank', 75) }}" min="0" max="100"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="min_today_sales" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Мин. продаж за сегодня</label>
                            <input type="number" name="min_today_sales" id="min_today_sales" value="{{ request('min_today_sales', 3) }}" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Сортировать по</label>
                            <select name="sort_by" id="sort_by"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                                <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Названию</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Цене</option>
                                <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Количеству</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Дате добавления</option>
                                <option value="sells_count" {{ request('sort_by', 'sells_count') == 'sells_count' ? 'selected' : '' }}>Популярности</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Направление</label>
                            <select name="sort_direction" id="sort_direction"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm">
                                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                                <option value="desc" {{ request('sort_direction', 'desc') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Применить
                            </button>

                            <a href="{{ route('books.top') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                Сбросить
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Метаданные -->
                @if(isset($meta) && !empty($meta))
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Найдено книг: {{ $meta['total_count'] ?? 0 }}
                    </div>
                @endif

                @if(empty($books))
                    <div class="text-center py-8">
                        <p class="text-gray-600 dark:text-gray-400">Книги не найдены.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($books as $book)
                            <div class="overflow-hidden shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="relative h-48 overflow-hidden bg-gray-200 dark:bg-gray-700">
                                    @if(isset($book['cover_url']) && $book['cover_url'])
                                        <img src="{{ $book['cover_url'] }}" alt="{{ $book['title'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-200">
                                        {{ $book['title'] }}
                                    </h3>

                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        @if(isset($book['authors']) && !empty($book['authors']))
                                            @if(count($book['authors']) == 1)
                                                Автор: {{ $book['authors'][0]['full_name'] }}
                                                @if(isset($book['authors'][0]['rank']))
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $book['authors'][0]['rank'] >= 85 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        Рейтинг: {{ $book['authors'][0]['rank'] }}
                                                    </span>
                                                @endif
                                            @else
                                                Авторы: {{ implode(', ', array_column($book['authors'], 'full_name')) }}
                                            @endif
                                        @else
                                            Автор: Неизвестен
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center mb-2">
                                        <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($book['price'], 2, '.', ' ') }} ₽
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            В наличии: {{ $book['quantity'] }}
                                        </div>
                                    </div>

                                    @if(isset($book['sells_count']))
                                        <div class="mb-4 text-sm">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 text-amber-800">
                                                Продаж: {{ $book['sells_count'] }}
                                            </span>
                                            @if(isset($book['sells_today']))
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded {{ $book['sells_today'] >= 3 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                    Сегодня: {{ $book['sells_today'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <a href="{{ route('books.show', $book['id']) }}"
                                       class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
