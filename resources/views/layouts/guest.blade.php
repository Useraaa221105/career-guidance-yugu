<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Карьерная траектория ЮГУ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0" style="background-color:#f1f5f9;">

            {{-- Логотип и название --}}
            <div class="mb-6 flex flex-col items-center gap-3">
                <a href="{{ route('test.index') }}">
                    <img src="/images/logo-yugu.jpg" alt="ЮГУ" class="h-16 w-auto rounded-xl shadow-sm">
                </a>
                <div class="text-center">
                    <div class="text-base font-bold" style="color:#1B52CC;">Карьерная траектория</div>
                    <div class="text-xs text-gray-400 mt-0.5">Сервис профориентации ЮГУ</div>
                </div>
            </div>

            {{-- Карточка формы --}}
            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-md overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>
