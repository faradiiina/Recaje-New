@extends('layouts.admin.app')

@section('title', '| Tambah Kategori')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Kriteria</h1>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-6">
            <div class="field-spacer">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA KRITERIA</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection 