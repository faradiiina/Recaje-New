@extends('layouts.app')

@section('title', ' - Reset Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 pt-28 md:pt-32">
        <div class="max-w-md w-full">
            <div style="border-radius: 32px;" class="bg-white dark:bg-gray-800 shadow-xl overflow-hidden p-6">
                <!-- Logo
                <div class="flex justify-center mb-4">
                    <a href="/" class="text-2xl font-bold text-gray-800 dark:text-white">Recaje</a>
                </div> -->
                
                <!-- Heading -->
                <h1 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-white">Reset Password</h1>
                
                @if (session('status'))
                    <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">
                        Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.
                    </p>
                    
                    <!-- Email -->
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white" 
                            placeholder="Email">
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="relative mb-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium cursor-pointer">
                            Kirim Link Reset Password
                        </button>
                    </div>
                </form>
                
                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Kembali ke halaman login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection 