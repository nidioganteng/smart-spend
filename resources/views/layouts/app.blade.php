<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'SmartSpend') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

        <div class="flex min-h-screen">

            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col ml-64">

                {{-- Top Bar --}}
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
                    <h1 class="text-lg font-semibold text-gray-800">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                    <a href="{{ route('profile.edit') }}"
                       class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        Profil Saya
                    </a>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 p-6">
                    {{ $slot }}
                </main>

            </div>
        </div>

    </body>
</html>
