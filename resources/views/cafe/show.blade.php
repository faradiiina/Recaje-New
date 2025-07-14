@extends('layouts.app')

@section('title', ' - ' . $cafe->nama)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
    .swiper-container {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        background: #f8f8f8;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #fff;
        background: rgba(0, 0, 0, 0.5);
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
    }

    .swiper-pagination-bullet-active {
        background: #fff;
    }

    /* Memastikan layout desktop bekerja dengan benar */
    @media (min-width: 768px) {
        .cafe-detail-layout {
            display: flex !important;
            flex-direction: row !important;
        }

        .cafe-photo-column {
            width: 33% !important;
            min-width: 280px !important;
            max-width: 350px !important;
        }

        .cafe-detail-column {
            width: 67% !important;
        }
    }
    
    /* Style untuk gallery scrollable */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    
    .hide-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari, Opera */
    }
    
    /* Efek bayangan untuk menandakan bisa scroll */
    .flex-nowrap.overflow-x-auto {
        position: relative;
    }
    
    .flex-nowrap.overflow-x-auto::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 20px;
        /* background: linear-gradient(to right, transparent, rgba(255,255,255,0.8) 80%); */
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-200 dark:bg-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:py-10">
        <div class="flex justify-between items-center mb-4 mt-4 ">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-950">Detail Cafe</h1>
            <div class="flex space-x-4 mt-4">
                <a href="{{ route('all-cafes') }}" class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
        <div class="mb-4">
            <!-- <a href="{{ route('all-cafes') }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Cafe -->
            <!-- </a> -->
        </div>

        <!-- Card Utama - Layout Responsif -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md my-4 mb-8">
            <!-- Layout Responsif -->
            <div class="flex flex-col md:flex-row cafe-detail-layout">
                <!-- Kolom Foto (Kiri) - Responsif -->
                <div class="w-full md:w-1/3 md:max-w-md cafe-photo-column">
                    <div class="p-4">
                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            @php
                            $images = $cafe->images;
                            @endphp
                            @if($images->count() > 0)
                            <div class="flex flex-col items-center">
                                <!-- Gambar utama -->
                                <div class="w-full h-auto flex items-center justify-center">
                                    <img id="mainCafeImage" src="{{ asset($images[0]->image_path) }}" alt="{{ $cafe->nama }}" class="w-full max-w-md h-auto object-cover max-h-[300px] md:max-h-none rounded shadow transition-all duration-200">
                                </div>
                                <!-- Thumbnail -->
                                <div class="flex flex-nowrap overflow-x-auto gap-2 pb-2 mt-1 max-w-[340px] mx-auto hide-scrollbar">
                                    @foreach($images as $idx => $image)
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $cafe->nama }}" data-idx="{{ $idx }}" class="cafe-thumb border-2 {{ $idx === 0 ? 'border-red-500' : 'border-transparent' }} rounded cursor-pointer w-16 h-16 flex-shrink-0 object-cover transition-all duration-200">
                                    @endforeach
                                </div>
                            </div>
                            @elseif($cafe->gambar)
                            <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" class="w-full h-auto object-cover max-h-[300px] md:max-h-none rounded shadow mb-4">
                            @else
                            <div class="w-full aspect-square bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <div class="flex space-x-4">
                                <button id="shareButton" class="flex items-center justify-center p-2 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </button>

                                @auth
                                <form id="favoriteForm" action="{{ route('cafes.favorite.toggle', ['id' => $cafe->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button id="favoriteButton" type="button" class="flex items-center justify-center p-2 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Auth::check() && Auth::user()->hasFavorited($cafe->id) ? 'text-red-500' : 'text-gray-600 dark:text-gray-400' }}"
                                            fill="{{ Auth::check() && Auth::user()->hasFavorited($cafe->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="flex items-center justify-center p-2 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </a>
                                @endauth
                            </div>

                            @if($cafe->latitude && $cafe->longitude)
                            <a href="https://www.google.com/maps?q={{ $cafe->latitude }},{{ $cafe->longitude }}" target="_blank" class="flex items-center justify-center bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition-colors text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                Google Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Rincian (Kanan) -->
                <div class="w-full md:w-2/3 md:border-l border-gray-200 dark:border-gray-700 cafe-detail-column">
                    <div class="p-4 md:p-6">
                        <!-- Badge Stars seperti di Shopee -->
                        <!-- <div class="inline-block bg-orange-500 text-white text-xs px-2 py-1 rounded mb-2">
                            Stars
                        </div> -->

                        <!-- Nama Cafe -->
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">{{ $cafe->nama }}</h1>

                        <!-- Rating stars seperti Shopee - horizontal layout -->
                        <div class="flex items-center mt-2">
                            @php
                            $totalRating = 0;
                            foreach($cafe->ratings as $rating) {
                            $totalRating += $rating->subcategory->value;
                            }
                            $avgRating = $cafe->ratings->count() > 0 ? $totalRating / $cafe->ratings->count() : 0;
                            $roundedRating = round($avgRating * 2) / 2;
                            @endphp

                            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300 mr-2">{{ number_format($avgRating, 1) }}</div>

                            <div class="text-yellow-400 flex mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$roundedRating)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-100" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    @elseif($i - 0.5 <= $roundedRating)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-100" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @endif
                                        @endfor
                            </div>

                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $cafe->ratings->count() }} penilaian</span>
                        </div>

                        <!-- Informasi lokasi -->
                        <div class="mt-5">
                            <!-- Lokasi -->
                            <!-- <p class="text-gray-600 dark:text-gray-300 flex items-center text-sm mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span class="font-medium">Lokasi:</span> <span class="ml-1">{{ $cafe->lokasi }}</span>
                            </p> -->

                            <!-- Area -->
                            @if($cafe->area)
                            <p class="text-gray-600 dark:text-gray-300 flex items-center text-sm mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                </svg>
                                <span class="font-medium">Area:</span> <span class="ml-1">{{ $cafe->area }}</span>
                            </p>
                            @endif
                        </div>

                        <!-- Pembatas -->
                        <div class="border-t border-gray-200 dark:border-gray-700 my-5"></div>

                        <!-- Rating dan kriteria - Style mirip Shopee dengan tabel -->
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Kriteria dan Rating</h2>

                        <div class="bg-white dark:bg-gray-800 rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <tbody>
                                        @foreach($cafe->ratings as $rating)
                                        <tr class="border-b border-gray-100 dark:border-gray-700 {{ $loop->last ? 'border-b-0' : '' }}">
                                            <td class="py-3 text-gray-700 dark:text-gray-300 font-medium w-1/3">{{ $rating->category->name }}</td>
                                            <td class="py-3">
                                                <div class="flex items-center">
                                                    <div class="text-yellow-400 flex mr-2">
                                                        @for ($i = 0; $i < $rating->subcategory->value; $i++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-100" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                            @endfor

                                                            @for ($i = $rating->subcategory->value; $i < 5; $i++)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                                @endfor
                                                    </div>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $rating->subcategory->name }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tombol aksi untuk Desktop -->
                        <!-- <div class="mt-8 hidden md:block">
                            <div class="flex flex-row gap-4">
                                <a href="{{ route('search-cafe') }}" class="flex-1 inline-flex items-center justify-center bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Cari Cafe Lainnya
                                </a> -->

                        <!-- <a href="{{ route('recommend-cafe') }}" class="flex-1 inline-flex items-center justify-center bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Dapatkan Rekomendasi
                                </a> -->
                        <!-- </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Bottom Container untuk mobile -->
        <!-- <div class="md:hidden fixed bottom-0 left-0 right-0 p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg z-40">
            <div class="flex flex-row gap-4">
                <a href="{{ route('search-cafe') }}" class="flex-1 inline-flex items-center justify-center bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari Lainnya
                </a>
            </div>
        </div> -->

        <!-- Spacer untuk mobile agar tombol fixed tidak menutupi konten -->
        <div class="h-28 md:h-0 block md:hidden"></div>
    </div>
</div>

<!-- Tambahkan CSS inline untuk memaksa layout -->
<style>
    /* Memastikan layout desktop bekerja dengan benar */
    @media (min-width: 768px) {
        .cafe-detail-layout {
            display: flex !important;
            flex-direction: row !important;
        }

        .cafe-photo-column {
            width: 33% !important;
            min-width: 280px !important;
            max-width: 350px !important;
        }

        .cafe-detail-column {
            width: 67% !important;
        }
    }
    
    /* Style untuk gallery scrollable */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    
    .hide-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari, Opera */
    }
    
    /* Efek bayangan untuk menandakan bisa scroll */
    .flex-nowrap.overflow-x-auto {
        position: relative;
    }
    
    .flex-nowrap.overflow-x-auto::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 20px;
        /* background: linear-gradient(to right, transparent, rgba(255,255,255,0.8) 80%); */
        pointer-events: none;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Galeri gambar Shopee style
        const thumbs = document.querySelectorAll('.cafe-thumb');
        const mainImg = document.getElementById('mainCafeImage');
        thumbs.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                // Ganti gambar utama
                mainImg.src = this.src;
                // Border aktif
                thumbs.forEach(t => t.classList.remove('border-red-500'));
                this.classList.add('border-red-500');
            });
        });

        // Kode untuk tombol share
        const shareButton = document.getElementById('shareButton');

        if (shareButton) {
            shareButton.addEventListener('click', function() {
                if (navigator.share) {
                    // Web Share API tersedia
                    navigator.share({
                            title: '{{ $cafe->nama }}',
                            text: 'Lihat informasi tentang {{ $cafe->nama }} di RECAJE',
                            url: window.location.href,
                        })
                        .then(() => console.log('Berhasil berbagi'))
                        .catch((error) => console.log('Error berbagi:', error));
                } else {
                    // Fallback jika Web Share API tidak tersedia
                    // Salin URL ke clipboard
                    navigator.clipboard.writeText(window.location.href)
                        .then(() => alert('URL disalin ke clipboard'))
                        .catch(() => alert('Tidak dapat menyalin URL'));
                }
            });
        }

        // Kode untuk tombol favorit
        const favoriteButton = document.getElementById('favoriteButton');
        const favoriteForm = document.getElementById('favoriteForm');

        if (favoriteButton && favoriteForm) {
            favoriteButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Tampilkan loading indicator
                const heartIcon = favoriteButton.querySelector('svg');
                heartIcon.classList.add('animate-pulse');

                // Siapkan data untuk request
                const formData = new FormData(favoriteForm);

                // Kirim request dengan fetch API
                fetch(favoriteForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        heartIcon.classList.remove('animate-pulse');

                        if (!response.ok) {
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    throw new Error(`HTTP error! Status: ${response.status}, Message: ${text}`);
                                }
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        if (data.success) {
                            // Update tampilan tombol
                            if (data.isFavorited) {
                                // Jika difavoritkan: isi warnanya (solid)
                                heartIcon.classList.add('text-red-500');
                                heartIcon.classList.remove('text-gray-600', 'dark:text-gray-400');
                                heartIcon.setAttribute('fill', 'currentColor');
                            } else {
                                // Jika dihapus dari favorit: kembalikan ke outline
                                heartIcon.classList.remove('text-red-500');
                                heartIcon.classList.add('text-gray-600', 'dark:text-gray-400');
                                heartIcon.setAttribute('fill', 'none');
                            }
                        } else if (data.message) {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        heartIcon.classList.remove('animate-pulse');
                        alert('Terjadi kesalahan: ' + error.message);
                    });
            });
        }
    });
</script>
@endsection