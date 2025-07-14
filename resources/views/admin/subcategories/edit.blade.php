@extends('layouts.admin.app')

@section('title', '| Edit Sub-Kategori')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Sub-Kriteria</h1>
        <a href="{{ route('admin.subcategories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.subcategories.update', $subcategory) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div class="field-spacer">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">KRITERIA</label>
                    <select name="category_id" id="category_id" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Pilih Kriteria</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <div class="field-spacer">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA SUB-KRITERIA</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $subcategory->name) }}" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>
                
                <div class="field-spacer">
                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NILAI SMART</label>
                    <input type="number" name="value" id="value" value="{{ old('value', $subcategory->value) }}" min="1" max="5" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required placeholder="1-5">
                </div>
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