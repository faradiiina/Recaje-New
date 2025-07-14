@extends('layouts.admin.app')

@section('title', '| Daftar Cafe')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Cafe</h1>
            <a href="{{ route('admin.cafes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
                Tambah
            </a>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">NAMA CAFE</th>
                        <th scope="col" class="px-6 py-3">HARGA</th>
                        <th scope="col" class="px-6 py-3">JARAK DENGAN PUSAT KOTA</th>
                        <th scope="col" class="px-6 py-3">JAM OPERASIONAL</th>
                        <th scope="col" class="px-6 py-3">FOTOGENIK</th>
                        <th scope="col" class="px-6 py-3">AREA</th>
                        <th scope="col" class="px-6 py-3">FASILITAS</th>
                        <th scope="col" class="px-6 py-3 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cafes as $cafe)
                        <tr class="bg-white dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $cafe->nama }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                @php
                                    $hargaRating = $cafe->harga;
                                @endphp
                                {{ $hargaRating ? $hargaRating->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                @php
                                    $jarakRating = $cafe->jarakKampus;
                                @endphp
                                {{ $jarakRating ? $jarakRating->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                @php
                                    $jamOperasionalCategory = \App\Models\Category::where('name', 'Jam Operasional')->first();
                                    $jamOperasionalRating = $jamOperasionalCategory ? $cafe->getCategoryRating($jamOperasionalCategory->id) : null;
                                @endphp
                                {{ $jamOperasionalRating ? $jamOperasionalRating->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                @php
                                    $fotogenikCategory = \App\Models\Category::where('name', 'Fotogenik')->first();
                                    $fotogenikRating = $fotogenikCategory ? $cafe->getCategoryRating($fotogenikCategory->id) : null;
                                @endphp
                                {{ $fotogenikRating ? $fotogenikRating->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $cafe->area ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                @php
                                    $fasilitasCategory = \App\Models\Category::where('name', 'Fasilitas')->first();
                                    $fasilitasRating = $fasilitasCategory ? $cafe->getCategoryRating($fasilitasCategory->id) : null;
                                @endphp
                                {{ $fasilitasRating ? $fasilitasRating->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('admin.cafes.edit', $cafe->id) }}?ts={{ time() }}" class="text-xs font-semibold uppercase hover:underline text-blue-600 dark:text-blue-400">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.cafes.destroy', $cafe) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cafe ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold uppercase hover:underline cursor-pointer text-blue-600 dark:text-blue-400">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-700">
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada cafe yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $cafes->links() }}
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                min-width: 100%;
            }
            
            th, td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endsection 