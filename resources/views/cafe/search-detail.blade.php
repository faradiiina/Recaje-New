@extends('layouts.app')

@section('title', ' - Detail Riwayat Pencarian')

@section('content')
<div class="bg-white dark:bg-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-950">Detail Riwayat Pencarian</h1>
            <div class="flex space-x-4">
                <a href="{{ route('cafe.search-history') }}" class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
        
        <!-- Informasi pencarian -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Pencarian</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pencarian</dt>
                        <dd class="mt-1 text-base font-medium text-gray-800 dark:text-white">{{ $history->created_at->format('d M Y H:i') }}</dd>
                    </dl>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                    <dd class="mt-1 text-base font-medium text-gray-800 dark:text-white">
                        {{ $history->location ?: 'Semua Area' }}
                    </dd>
                </div>
            </div>
        </div>
        
        <!-- Bobot Kriteria -->
        <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Bobot Kriteria</h2>
            @if(!empty($weights))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Kriteria
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Bobot
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Bobot Normalisasi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $totalWeight = array_sum($weights);
                            @endphp
                            @foreach($weights as $categoryId => $weight)
                                @php
                                    $category = \App\Models\Category::find($categoryId);
                                    $normWeight = $totalWeight > 0 ? ($weight / $totalWeight) * 100 : 0;
                                @endphp
                                @if($category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ $weight }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ number_format($normWeight, 2) }}%
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Tidak ada data bobot kriteria</p>
            @endif
        </div> -->
        
        <!-- Hasil -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Hasil Pencarian</h2>
            @if($rankedCafes->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Tidak ada hasil pencarian</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rankedCafes as $cafe)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-700">
                            @if($cafe->gambar)
                                <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-r from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $cafe->nama }}</h3>
                                <!-- <p class="text-gray-600 dark:text-gray-300 mb-3">{{ $cafe->lokasi }}</p> -->
                                
                                <!-- Detail skor -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Skor SMART</span>
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($cafe->smart_score, 2) }}</span>
                                    </div>
                                    
                                    @if(!empty($cafe->smart_details))
                                        <details class="group mt-3">
                                            <summary class="font-medium text-sm cursor-pointer text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 flex items-center">
                                                <span>Detail Perhitungan</span>
                                                <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </summary>
                                            <div class="mt-2 text-xs bg-gray-50 dark:bg-gray-800 p-2 rounded-lg">
                                                <table class="min-w-full">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left py-1 px-2 text-gray-500">Kriteria</th>
                                                            <th class="text-right py-1 px-2 text-gray-500">Bobot</th>
                                                            <th class="text-right py-1 px-2 text-gray-500">Nilai</th>
                                                            <th class="text-right py-1 px-2 text-gray-500">Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cafe->smart_details as $category => $info)
                                                            <tr>
                                                                <td class="text-left py-1 px-2 text-gray-700 dark:text-gray-300">{{ $category }}</td>
                                                                <td class="text-right py-1 px-2 text-gray-700 dark:text-gray-300">{{ number_format($info['bobot_normal'] * 100, 0) }}%</td>
                                                                <td class="text-right py-1 px-2 text-gray-700 dark:text-gray-300">{{ number_format($info['nilai'], 1) }}</td>
                                                                <td class="text-right py-1 px-2 text-gray-700 dark:text-gray-300">{{ number_format($info['skor_kriteria'], 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </details>
                                    @endif
                                    
                                    <div class="mt-4 text-right">
                                        <a href="{{ route('cafes.show', $cafe->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm font-medium">
                                            Lihat Detail Café
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 