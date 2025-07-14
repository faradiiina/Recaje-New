<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recaje - Verifikasi Email</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="{{ asset('css/disable-keyboard.css') }}" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/disable-keyboard.js') }}"></script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen">
        <div class="flex-grow flex items-center justify-center p-4">
            <div class="max-w-md w-full">
                <div style="border-radius: 32px;" class="bg-white dark:bg-gray-800 shadow-xl overflow-hidden p-6">
                    <!-- Logo -->
                    <div class="flex justify-center mb-4">
                        <a href="/" class="text-2xl font-bold text-gray-800 dark:text-white">Recaje</a>
                    </div>
                    
                    <!-- Heading -->
                    <h1 class="text-2xl font-bold text-center mb-4 text-gray-800 dark:text-white">Verifikasi Email Anda</h1>
                    
                    
                    @if (session('resent'))
                        <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-4 rounded-lg mb-6">
                            Email verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif
                    
                    <p class="text-gray-600 dark:text-gray-300 text-center mb-6 px-8">
                        Sebelum melanjutkan, silakan periksa email Anda untuk tautan verifikasi.
                        Jika Anda tidak menerima email tersebut, klik tombol di bawah untuk meminta email verifikasi baru.
                    </p>
                    
                    <form method="POST" action="{{ route('verification.resend') }}" class="mb-6">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                    
                    <div class="text-center mt-8">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Keluar
                        </a>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="bg-gray-800 text-white py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>&copy; 2024 Recaje. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html> 