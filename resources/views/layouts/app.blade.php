<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
                        }
                    }
                }
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }

            /* Стили для всплывающих уведомлений */
            #book-notification {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 9999;
                transition: all 0.3s ease-in-out;
                transform: translateY(150%);
                opacity: 0;
            }

            #book-notification.show {
                transform: translateY(0);
                opacity: 1;
            }

        </style>
    </head>
    <body class="font-sans antialiased {{ config('app.debug') ? 'debug' : '' }}">

        <!-- Контейнер для уведомлений о новых книгах -->
        <div id="book-notification" class="max-w-sm bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-primary-300 dark:border-primary-700">
            <div class="px-4 py-3 bg-primary-100 dark:bg-primary-900 border-b border-primary-200 dark:border-primary-800 flex justify-between items-center">
                <h3 class="font-medium text-primary-800 dark:text-primary-200">Новая книга!</h3>
                <button id="close-notification" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 flex">
                <div id="book-cover" class="w-16 h-24 mr-4 bg-gray-200 dark:bg-gray-700 flex-shrink-0 overflow-hidden">
                    <img id="book-cover-img" src="" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h4 id="book-title" class="font-semibold text-gray-800 dark:text-gray-200 mb-1"></h4>
                    <p id="book-price" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></p>
                    <a id="book-link" href="#" class="inline-block px-3 py-1 text-sm text-white bg-primary-600 hover:bg-primary-700 rounded">
                        Посмотреть
                    </a>
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
