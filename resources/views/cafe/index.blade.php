@extends('layouts.app')

@section('title', ' - Semua Cafe')

@section('content')
<div class="bg-gray-200 dark:bg-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-950 mb-4 md:mb-0">Daftar Cafe di Jember</h1>
            
            <div class="w-full md:w-auto">
                <form action="{{ route('all-cafes') }}" method="GET" class="flex space-x-2">
                    <div class="relative flex-grow">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari cafe..." 
                            value="{{ request('search') }}" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 dark:text-gray-950">Temukan cafe favorit Anda dari berbagai pilihan yang tersedia.</p>
        </div>

        @if($cafes->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">Belum Ada Data Cafe</h3>
            <p class="text-gray-500 dark:text-gray-400">
                Tidak ada data cafe yang tersedia saat ini.
            </p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($cafes as $cafe)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-2xl" style="transform: translateZ(0); backface-visibility: hidden;">
                <div style="height: 200px; min-height: 200px; max-height: 200px; overflow: hidden; position: relative;" class="w-full flex-shrink-0 rounded-lg">
                    @if($cafe->gambar)
                    <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" class="bg-gradient-to-r from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $cafe->nama }}</h3>
                    <!-- <p class="text-gray-600 dark:text-gray-300 mb-1 text-sm">{{ $cafe->lokasi }}</p> -->
                    @if($cafe->area)
                    <p class="text-gray-600 dark:text-gray-300 mb-3 text-sm">Area: {{ $cafe->area }}</p>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($cafe->ratings->take(4) as $rating)
                                <div class="col-span-1">
                                    <span class="font-medium text-xs">{{ $rating->category->name }}</span>
                                    <div class="text-yellow-500">
                                        @for ($i = 0; $i < $rating->subcategory->value; $i++)
                                            ★
                                            @endfor
                                            @for ($i = $rating->subcategory->value; $i < 5; $i++)
                                                ☆
                                                @endfor
                                                </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 text-right">
                                <a href="{{ route('cafes.show', $cafe->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm font-medium cursor-pointer">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 ">
                {{ $cafes->links() }}
            </div>
            @endif
        </div>
    </div>
    @endsection