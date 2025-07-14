@extends('layouts.admin.app')

@section('title', '| Detail Cafe')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Cafe: {{ $cafe->nama }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.cafes.edit', $cafe) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
                    Edit
                </a>
                <a href="{{ route('admin.cafes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Cafe</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $cafe->nama }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lokasi</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $cafe->lokasi }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Area</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $cafe->area ?? 'Tidak ada data' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Jarak dari Kampus</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $cafe->jarak_kampus }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kisaran Harga</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $cafe->harga }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">WiFi</p>
                            <div class="mt-1">
                                <span class="inline-flex text-xs leading-5 font-semibold rounded-full px-2 py-1 {{ $cafe->wifi ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $cafe->wifi ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Fasilitas</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $cafe->fasilitas) as $fasilitas)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                {{ trim($fasilitas) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="md:col-span-1">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Foto Cafe</h2>
                    
                    @if($cafe->gambar)
                        <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" class="w-full h-auto rounded-lg">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada foto</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Lainnya</h2>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada</p>
                        <p class="text-md font-medium text-gray-900 dark:text-white">{{ $cafe->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="space-y-2 mt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                        <p class="text-md font-medium text-gray-900 dark:text-white">{{ $cafe->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 