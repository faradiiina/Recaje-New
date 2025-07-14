@extends('layouts.app')

@section('title', ' - Daftar')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 pt-28 md:pt-32">
        <div class="max-w-md w-full">
            <div style="border-radius: 32px;" class="bg-white dark:bg-gray-800 shadow-xl overflow-hidden p-6">
                <!-- Logo
                <div class="flex justify-center mb-4">
                    <a href="/" class="text-2xl font-bold text-gray-800 dark:text-white">Recaje</a>
                </div> -->
                
                <!-- Heading -->
                <h1 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white">Buat Akun Baru</h1>
                
                <!-- Google Login Button -->
                <!-- <div class="mb-4">
                    <a href="{{ route('login.google') }}" class="flex justify-center items-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 w-full">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div> -->
                
                <!-- Divider -->
                <!-- <div class="relative mb-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Atau daftar dengan</span>
                    </div>
                </div> -->
                
                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div class="relative">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white" 
                            placeholder="Nama Lengkap">
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white" 
                            placeholder="Email">
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="relative">
                        <input id="password" type="password" name="password" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white" 
                            placeholder="Password">
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white" 
                            placeholder="Konfirmasi Password">
                    </div>
                    
                    <!-- Terms and Privacy -->
                    <div class="flex items-start">
                        <div class="relative flex items-center">
                            <input id="terms" type="checkbox" name="terms" required 
                                class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-600 dark:text-gray-400 cursor-pointer">
                                Saya menyetujui <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Syarat dan Ketentuan</a> serta <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Kebijakan Privasi</a>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="relative mb-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium cursor-pointer">
                            Daftar
                        </button>
                    </div>
                </form>
                
                @if (session('status') == 'verification-link-sent')
                <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-4 rounded-lg mb-6">
                    Email verifikasi telah dikirim ke alamat email Anda.
                </div>
                <script>
                    window.location.href = "{{ route('verification.notice') }}";
                </script>
                @endif
                
                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection 