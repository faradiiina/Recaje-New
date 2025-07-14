@extends('layouts.app')

@section('title', 'Cafe Favorit')

@section('content')
<div class="bg-gray-200 dark:bg-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-950 mb-4 md:mb-0">Cafe Favorit Saya</h1>
        </div>

        @if($favorites->isEmpty())
        <div class="bg-gray-200 dark:bg-gray-200 rounded-lg p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h3 class="text-xl font-medium text-gray-700 dark:text-gray-700 mb-2">Belum Ada Cafe Favorit</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-4">
                Anda belum menambahkan cafe ke daftar favorit
            </p>
            <a href="{{ route('all-cafes') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Jelajahi Cafe
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($favorites as $favorite)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-2xl" style="transform: translateZ(0); backface-visibility: hidden;">
                <div style="height: 200px; min-height: 200px; max-height: 200px; overflow: hidden; position: relative;" class="w-full flex-shrink-0 rounded-lg">
                    @if($favorite->cafe->images->count() > 0)
                    <img src="{{ asset($favorite->cafe->images[0]->image_path) }}" alt="{{ $favorite->cafe->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" class="bg-gradient-to-r from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $favorite->cafe->nama }}</h3>
                    @if($favorite->cafe->area)
                    <p class="text-gray-600 dark:text-gray-300 mb-3 text-sm">Area: {{ $favorite->cafe->area }}</p>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($favorite->cafe->ratings->take(4) as $rating)
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

                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('cafes.show', $favorite->cafe->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm font-medium cursor-pointer">
                                    Lihat Detail
                                </a>
                                <form action="{{ route('cafes.favorite.toggle', ['id' => $favorite->cafe->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection 