@extends('layouts.app')

@section('title', ' - Riwayat Pencarian')

@section('content')
<div class="bg-gray-200 dark:bg-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-950">Riwayat Pencarian</h1>
        
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-left gap-4">
            <p class="text-gray-600 dark:text-gray-950">Berikut adalah daftar pencarian yang pernah Anda lakukan.</p>
            <!-- <a href="{{ route('search-cafe') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a7 7 0 100 14 7 7 0 000-14zm-9 7a9 9 0 1118 0 9 9 0 01-18 0z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M13.586 11.586a1 1 0 01-1.414 1.414L10 10.828 7.879 13a1 1 0 01-1.414-1.414l2.828-2.829a1 1 0 011.414 0l2.879 2.829z" clip-rule="evenodd" />
                </svg>
                Pencarian Baru
            </a> -->
        </div>
        
        @if($histories->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-950 mb-2">Belum Ada Riwayat Pencarian</h3>
                <p class="text-gray-500 dark:text-gray-950">
                    Anda belum pernah melakukan pencarian cafe. Mulai pencarian pertama Anda sekarang!
                </p>
            </div>
        @else
            <div class="bg-gray-200 dark:bg-gray-900 rounded-lg shadow-md overflow-hidden">
                <style>
                    @media (min-width: 768px) {
                        .desktop-table { display: table; }
                        .mobile-cards { display: none; }
                    }
                    @media (max-width: 767px) {
                        .desktop-table { display: none; }
                        .mobile-cards { display: block; }
                    }
                </style>
                <!-- Desktop View -->
                <div class="w-full overflow-x-auto rounded-lg">
                    <table class="desktop-table w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-100">
                            <tr>
                                <th scope="col" class="px-3 lg:px-6 py-3 text-center text-xs font-medium text-gray-200 dark:text-gray-200 uppercase tracking-wider">
                                    Tanggal Pencarian
                                </th>
                                <th scope="col" class="px-3 lg:px-6 py-3 text-center text-xs font-medium text-gray-200 dark:text-gray-200 uppercase tracking-wider">
                                    Kriteria
                                </th>
                                <th scope="col" class="px-3 lg:px-6 py-3 text-center text-xs font-medium text-gray-200 dark:text-gray-200 uppercase tracking-wider">
                                    Hasil Teratas
                                </th>
                                <th scope="col" class="px-3 lg:px-6 py-3 text-center text-xs font-medium text-gray-200 dark:text-gray-200 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($histories as $history)
                                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors my-2">                                    <td class="px-3 lg:px-6 py-5 lg:py-8 whitespace-nowrap">                                        <div class="text-xs text-gray-900 dark:text-gray-100">{{ $history->created_at->format('d M Y') }}</div>                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $history->created_at->format('H:i') }}</div>                                    </td>                                    <td class="px-3 lg:px-6 py-5 lg:py-8">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            @php
                                                $criteria = [];
                                                if (!empty($history->weights)) {
                                                    foreach ($history->weights as $categoryId => $weight) {
                                                        if ($weight > 0) {
                                                            $category = \App\Models\Category::find($categoryId);
                                                            if ($category) {
                                                                $criteria[] = $category->name . ' (' . round(($weight / array_sum($history->weights) * 100), 0) . '%)';
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(count($criteria) > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($criteria as $c)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ $c }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400 text-xs">Tidak ada kriteria khusus</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 lg:px-6 py-4 lg:py-6">
                                        @if(!empty($history->results))
                                            <div class="space-y-2">
                                                @php $count = 0; @endphp
                                                @foreach($history->results as $cafeId => $result)
                                                    @if($count < 3)
                                                        <div class="flex items-center gap-2">
                                                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-700 text-xs font-medium text-gray-800 dark:text-gray-200">{{ $count + 1 }}</span>
                                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $result['name'] ?? 'Cafe #'.$cafeId }}
                                                                <span class="text-blue-500 dark:text-blue-400 ml-1 text-xs font-medium">({{ number_format($result['score'], 2) }})</span>
                                                            </span>
                                                        </div>
                                                        @php $count++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada hasil</span>
                                        @endif
                                    </td>
                                                                        <td class="px-3 lg:px-6 py-5 lg:py-8 whitespace-nowrap text-right text-sm font-medium">                                        <a href="{{ route('cafe.smart-search-detail', $history->id) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-1.5 border border-transparent text-xs lg:text-sm leading-4 rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />                                            </svg>                                            Detail                                        </a>                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    
                <!-- Mobile View (smaller than md) -->
                <div class="mobile-cards space-y-4">
                    @foreach($histories as $history)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow mb-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $history->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $history->created_at->format('H:i') }}</div>
                                </div>
                                <a href="{{ route('cafe.smart-search-detail', $history->id) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                            </div>
                            
                            <!-- <div class="mb-3">
                                <div class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 mb-1">Kriteria:</div>
                                @php
                                    $criteria = [];
                                    if (!empty($history->weights)) {
                                        foreach ($history->weights as $categoryId => $weight) {
                                            if ($weight > 0) {
                                                $category = \App\Models\Category::find($categoryId);
                                                if ($category) {
                                                    $criteria[] = $category->name . ' (' . round(($weight / array_sum($history->weights) * 100), 0) . '%)';
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @if(count($criteria) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($criteria as $c)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $c }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">Tidak ada kriteria khusus</span>
                                @endif
                            </div> -->
                            
                            <div>
                                <div class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 mb-2">Hasil Teratas:</div>
                                @if(!empty($history->results))
                                    <div class="space-y-2">
                                        @php $count = 0; @endphp
                                        @foreach($history->results as $cafeId => $result)
                                            @if($count < 3)
                                                <div class="flex items-center gap-2">
                                                    <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-700 text-xs font-medium text-gray-800 dark:text-gray-200">{{ $count + 1 }}</span>
                                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $result['name'] ?? 'Cafe #'.$cafeId }}
                                                        <span class="text-blue-500 dark:text-blue-400 ml-1 text-xs font-medium">({{ number_format($result['score'], 2) }})</span>
                                                    </span>
                                                </div>
                                                @php $count++; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada hasil</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-6">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 