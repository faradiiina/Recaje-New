@extends('layouts.app')

@section('title', ' - Rekomendasi Café')

@section('content')
    <div class="py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white text-center">Rekomendasikan Saya Café Terbaik</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 text-center mb-8">Kami akan memberikan rekomendasi café terbaik berdasarkan preferensi Anda</p>
            
            <!-- Main Container with Sidebar and Content -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar - Preference Form -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden p-6 sticky top-6">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Preferensi Saya</h2>
                        
                        <div class="space-y-6">
                            <!-- Atmosphere Preference -->
                            <div>
                                <p class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Suasana yang saya sukai:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Tenang</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Nyaman</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Ramai</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Romantis</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Cafe Type Preference -->
                            <div>
                                <p class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Jenis café yang saya sukai:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Coffee Shop</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Boba/Tea</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Dessert</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Breakfast</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Purpose Preference -->
                            <div>
                                <p class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tujuan saya ke café:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Kerja</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Santai</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Meeting</span>
                                    </label>
                                    <label class="flex p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" class="h-5 w-5 rounded-md border-gray-300 text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:ring-offset-0 cursor-pointer mr-2">
                                        <span class="text-gray-700 dark:text-gray-300">Kencan</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pt-4">
                                <button class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium">
                                    Berikan Rekomendasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content - Results -->
                <div class="w-full lg:w-2/3">
                    <!-- Results Container -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden p-6">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Rekomendasi Café untuk Anda</h2>
                        
                        <!-- Results Placeholder -->
                        <div class="text-center py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">Pilih preferensi Anda</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-500">Rekomendasi café akan muncul di sini</p>
                        </div>
                        
                        <!-- Example Results (Hidden by default, would be shown after search) -->
                        <div class="hidden">
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Cafe Item 1 -->
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 flex flex-col md:flex-row">
                                    <div class="w-full md:w-1/3 mb-4 md:mb-0 md:mr-4">
                                        <div class="h-48 bg-gray-300 dark:bg-gray-700 rounded-lg"></div>
                                    </div>
                                    <div class="w-full md:w-2/3">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Nama Café 1</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Rekomendasi 95%
                                            </span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                            <span class="ml-1 text-gray-600 dark:text-gray-400">4.5 (120 reviews)</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mb-2">Jakarta Selatan</p>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Café dengan suasana nyaman dan tenang, cocok untuk bekerja atau bersantai.</p>
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Free Wifi</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Stop Kontak</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Nyaman</span>
                                        </div>
                                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            Lihat Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 