@extends('layouts.app')

@section('title', ' - Landing Page')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Welcome to Recaje</h1>
            <p class="text-xl mb-8">Your ultimate solution for modern web applications</p>
            <a href="#features" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-200 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800 dark:text-white">Our Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div style="border-radius: 24px;" class="bg-white dark:bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <div class="flex justify-center mb-4" style="height: 220px; overflow: hidden;">
                        <img src="{{ asset('assets/images/Feature 1.webp') }}" alt="Pencarian Café" style="width: 100%; height: 220px; object-fit: cover;" class="rounded-lg">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-center text-gray-800 dark:text-white">Temukan Café-mu Sesuai Kriteriamu</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-center mb-6 px-8">Pilih berbagai kriteria seperti harga, suasana, fasilitas, dan lokasi untuk menemukan kafe yang sempurna sesuai kebutuhan Anda. Kami membantu Anda memfilter hanya kafe yang memenuhi preferensi Anda.</p>
                    <div class="flex justify-center mb-6">
                        @auth
                            <a href="{{ route('search-cafe') }}" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition duration-300 shadow-md font-medium cursor-pointer">Coba Sekarang</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition duration-300 shadow-md font-medium cursor-pointer">Coba Sekarang</a>
                        @endauth
                    </div>
                </div>
                <div style="border-radius: 24px;" class="bg-white dark:bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <div class="flex justify-center mb-4" style="height: 220px; overflow: hidden;">
                        <img src="{{ asset('assets/images/Feature 2.webp') }}" alt="Rekomendasi Café" style="width: 100%; height: 220px; object-fit: cover;" class="rounded-lg">
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-center text-gray-800 dark:text-white">Cari Daftar Cafe di Jember</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-center mb-6 px-8">Lihat daftar cafe yang ada di Jember dan temukan cafe yang sesuai dengan kebutuhan Anda. Kami menyediakan informasi lengkap tentang cafe, termasuk lokasi, jam operasional, dan fasilitas yang tersedia.</p>
                    <div class="flex justify-center mb-6">
                        @auth
                            <a href="{{ route('all-cafes') }}" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition duration-300 shadow-md font-medium cursor-pointer">Coba Sekarang</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition duration-300 shadow-md font-medium cursor-pointer">Coba Sekarang</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-20 bg-gray-200 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800 dark:text-white">About Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Our Story</h3>
                    <p class="text-gray-600 dark:text-gray-300">CaféSmart adalah platform sistem pendukung keputusan yang dirancang khusus untuk membantu mahasiswa dan masyarakat di Kabupaten Jember dalam menemukan café yang paling sesuai dengan kebutuhan mereka. Melalui pendekatan berbasis metode SMART (Simple Multi Attribute Rating Technique), kami menghadirkan rekomendasi yang objektif, cepat, dan akurat berdasarkan kriteria penting seperti harga, kecepatan wifi, jam operasional, jarak dari kampus, serta kelengkapan fasilitas.</p>
                </div>
                <div class="flex justify-center">
                    <img src="{{ asset('assets/images/About Us.png') }}" alt="About Us" class="rounded-lg shadow-lg" style="max-height: 400px; width: auto;">
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-20 bg-gray-200 dark:bg-gray-800 mb-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800 dark:text-white">Contact Us</h2>
            <div class="max-w-md mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 px-4 py-2 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 px-4 py-2 @error('email') border-red-500 @enderror" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                        <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 px-4 py-2 @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Send Message</button>
                </form>
            </div>
        </div>
    </section>
@endsection
