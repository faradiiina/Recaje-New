@extends('layouts.admin.app')

@section('title', '| Tambah Sub-Kategori')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Sub-Kriteria</h1>
        <a href="{{ route('admin.subcategories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.subcategories.store') }}" method="POST" class="space-y-6" id="subcategory-form">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div class="field-spacer">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">KRITERIA</label>
                    <select name="category_id" id="category_id" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Pilih Kriteria</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $selectedCategoryId) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="field-spacer">
                <div id="subcategory-container" class="space-y-6">
                    <div class="subcategory-item p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA SUB-KRITERIA</label>
                                <input type="text" name="subcategories[0][name]" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Masukkan nama sub-kategori" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NILAI SMART</label>
                                <input type="number" name="subcategories[0][value]" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="1-5" min="1" max="5" required>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="remove-subcategory px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" id="add-subcategory" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Simpan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing subcategory form');
        const container = document.getElementById('subcategory-container');
        const addButton = document.getElementById('add-subcategory');
        const form = document.getElementById('subcategory-form');
        let subcategoryCount = 1;
        
        console.log('Container:', container);
        console.log('Add button:', addButton);
        console.log('Form:', form);
        
        // Tampilkan tombol hapus pada item pertama jika ada lebih dari satu item
        updateRemoveButtons();
        
        addButton.addEventListener('click', function() {
            console.log('Add button clicked, adding new subcategory');
            const item = document.createElement('div');
            item.className = 'subcategory-item p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg';
            item.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA SUB-KATEGORI</label>
                        <input type="text" name="subcategories[${subcategoryCount}][name]" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Masukkan nama sub-kategori" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NILAI SMART</label>
                        <input type="number" name="subcategories[${subcategoryCount}][value]" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="1-5" min="1" max="5" required>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="remove-subcategory px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
            `;
            container.appendChild(item);
            subcategoryCount++;
            updateRemoveButtons();
            console.log('New subcategory added, count:', subcategoryCount);
        });
        
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-subcategory') || e.target.closest('.remove-subcategory')) {
                console.log('Remove button clicked');
                e.target.closest('.subcategory-item').remove();
                updateRemoveButtons();
            }
        });
        
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            const formData = new FormData(form);
            console.log('Form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
        
        function updateRemoveButtons() {
            const items = container.querySelectorAll('.subcategory-item');
            const removeButtons = container.querySelectorAll('.remove-subcategory');
            
            console.log('Updating remove buttons, items count:', items.length);
            
            // Tampilkan tombol hapus jika ada lebih dari satu item
            removeButtons.forEach(button => {
                button.style.display = items.length > 1 ? 'inline-flex' : 'none';
            });
        }
    });
</script>
@endpush
@endsection