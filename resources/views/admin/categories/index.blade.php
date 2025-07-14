@extends('layouts.admin.app')

@section('title', '| Daftar Kategori')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Kriteria</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
            Tambah
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-hidden rounded-lg">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="h-12">
                    <th scope="col" class="px-6 py-3 rounded-tl-lg">
                        NO
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NAMA KRITERIA
                    </th>
                    <th scope="col" class="px-6 py-3 text-right rounded-tr-lg">
                        AKSI
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                    <tr class="h-16 bg-white dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 transition-colors hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap {{ $loop->first ? 'rounded-tl-lg' : '' }} {{ $loop->last ? 'rounded-bl-lg' : '' }}">
                            {{ $index + 1 }}
                        </th>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 text-right {{ $loop->first ? 'rounded-tr-lg' : '' }} {{ $loop->last ? 'rounded-br-lg' : '' }}">
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-semibold uppercase hover:underline text-blue-600 dark:text-blue-400">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
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
                    <tr class="h-16 bg-white dark:bg-gray-700">
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 rounded-b-lg">
                            Tidak ada kategori yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 