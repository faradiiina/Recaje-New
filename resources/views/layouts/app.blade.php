<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Recaje') }}@yield('title', '')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen">
        @include('layouts.header')
        
        <!-- <div class="stepper-wrapper" style="margin-top: 64px; position: relative; z-index: 10;">
            @yield('stepper')
        </div> -->

        <main class="flex-grow bg-gray-200 dark:bg-gray-200">
            @yield('content')
        </main>
        
        <!-- <footer class="bg-gray-800 text-white py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>&copy; 2024 Recaje. All rights reserved.</p>
                </div>
            </div>
        </footer> -->
        
        @yield('scripts')
    </body>
</html> 