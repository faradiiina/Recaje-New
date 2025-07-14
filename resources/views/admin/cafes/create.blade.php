@extends('layouts.admin.app')

@section('title', 'Tambah Cafe')

@section('styles')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
    <style>
        #map { 
            height: 400px !important; 
            width: 100% !important;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            position: relative !important;
            z-index: 1 !important;
        }
        .field-spacer {
            margin-bottom: 20px;
        }
        select {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 6px;
            padding-right: 6px;
        }
        .leaflet-container {
            z-index: 1 !important;
        }
        .leaflet-control-container {
            z-index: 1000 !important;
        }
        /* Crop Modal Styles */
        .crop-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
        }
        
        .crop-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
        }
        
        .crop-img-container {
            max-height: 60vh;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        .crop-controls {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        .crop-controls button {
            margin-left: 10px;
        }
        
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            max-width: 340px;
        }
        
        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid #ddd;
        }

        .sortable-indicator {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0,0,0,0.5);
            color: white;
            z-index: 5;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sortable-item:hover .sortable-indicator {
            opacity: 1;
        }

        .delete-btn {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #ef4444;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            z-index: 20;
            opacity: 0;
            transition: all 0.3s;
            cursor: pointer;
            transform: translate(50%, -50%) scale(0.5);
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .sortable-item:hover .delete-btn {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }

        .delete-btn:hover {
            background-color: #dc2626;
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Cafe Baru</h1>
            <a href="{{ route('admin.cafes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Ada kesalahan dalam pengisian form:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cafes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Nama Cafe dan Peta -->
                <div class="space-y-6">
                    <div class="field-spacer">
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA CAFE</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>

                    <div class="field-spacer">
                        <label for="area" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">AREA</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_indoor" value="Indoor" {{ is_array(old('area')) && in_array('Indoor', old('area')) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                <label for="area_indoor" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Indoor</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_semi_outdoor" value="Semi Outdoor" {{ is_array(old('area')) && in_array('Semi Outdoor', old('area')) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                <label for="area_semi_outdoor" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Semi Outdoor</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_outdoor" value="Outdoor" {{ is_array(old('area')) && in_array('Outdoor', old('area')) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                <label for="area_outdoor" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Outdoor</label>
                            </div>
                        </div>
                    </div>

                    <div class="field-spacer">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PILIH LOKASI DI PETA</label>
                        <div id="map"></div>
                        <p class="text-sm text-gray-500 mt-1">Klik pada peta untuk memilih lokasi cafe. Marker akan muncul dan koordinat akan diisi otomatis.</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Semua field lainnya -->
                <div class="space-y-6">
                    <div class="field-spacer">
                        <label for="koordinat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">KOORDINAT (LATITUDE, LONGITUDE)</label>
                        <input type="text" name="koordinat" id="koordinat" value="{{ old('koordinat') }}" placeholder="Contoh: -8.160591, 113.721279" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        
                        <!-- Hidden fields for latitude and longitude -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    <div class="field-spacer">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">RENTANG HARGA</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harga_termurah" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Harga Termurah (Rp)</label>
                                <input type="text" name="harga_termurah_display" id="harga_termurah_display" value="{{ old('harga_termurah') ? number_format(old('harga_termurah'), 0, ',', '.') : '' }}" placeholder="0" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <input type="hidden" name="harga_termurah" id="harga_termurah" value="{{ old('harga_termurah') }}">
                            </div>
                            <div>
                                <label for="harga_termahal" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Harga Termahal (Rp)</label>
                                <input type="text" name="harga_termahal_display" id="harga_termahal_display" value="{{ old('harga_termahal') ? number_format(old('harga_termahal'), 0, ',', '.') : '' }}" placeholder="0" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <input type="hidden" name="harga_termahal" id="harga_termahal" value="{{ old('harga_termahal') }}">
                            </div>
                        </div>
                        <div id="harga-error" class="text-red-500 text-sm mt-1" style="display: none;"></div>
                        <p class="text-sm text-gray-500 mt-1">Masukkan rentang harga menu di cafe ini (opsional)</p>
                    </div>

                    @foreach($categories as $category)
                        <div class="field-spacer">
                            <label for="category_{{ $category->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ strtoupper($category->name) }}</label>
                            <select name="category_ratings[{{ $category->id }}]" id="category_{{ $category->id }}" style="min-height: 48px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 subcategory-select" data-category-id="{{ $category->id }}" required>
                                <option value="">Pilih {{ $category->name }}</option>
                                @foreach($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_ratings.'.$category->id) == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

                    <div class="field-spacer">
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">GAMBAR CAFE (OPSIONAL)</label>
                        <input type="file" name="gambar[]" id="gambar" multiple style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <p class="text-sm text-gray-500 mt-1">Anda dapat memilih lebih dari satu gambar. Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran per file: 2MB</p>
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

    <!-- Crop Modal -->
    <div id="cropModal" class="crop-modal">
        <div class="crop-modal-content">
            <div class="crop-img-container">
                <img id="cropImage" src="" alt="Crop Image">
            </div>
            <div class="crop-controls">
                <button id="cropCancel" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                <button id="cropConfirm" class="px-4 py-2 bg-blue-600 text-white rounded">Crop & Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Map scripts -->
<script>
    $(document).ready(function() {
        // Tunggu sebentar untuk memastikan semua elemen sudah dimuat
        setTimeout(function() {
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }

            try {
                // Pastikan Leaflet sudah dimuat
                if (typeof L === 'undefined') {
                    console.error('Leaflet library not loaded');
                    return;
                }

                console.log('Creating map instance...');
                const map = L.map('map', {
                    center: [-8.159765315131203, 113.72309609838182], // Pusat di Jember sebagai default
                    zoom: 13,
                    zoomControl: true,
                    attributionControl: true,
                    preferCanvas: true
                });

                console.log('Adding tile layer...');
                const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    minZoom: 10.5,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                });

                tileLayer.addTo(map);

                let marker;

                // Event untuk menambahkan marker saat peta diklik
                map.on('click', function(e) {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);
                    document.getElementById('latitude').value = e.latlng.lat;
                    document.getElementById('longitude').value = e.latlng.lng;
                    document.getElementById('koordinat').value = `${e.latlng.lat}, ${e.latlng.lng}`;
                });

                // Trigger resize untuk memastikan peta ditampilkan dengan benar
                setTimeout(function() {
                    map.invalidateSize();
                    console.log('Map size invalidated');
                }, 100);

                console.log('Map initialized successfully');
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }, 1000);
    });
</script>

<!-- Cropper Interactive -->
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    var input = document.getElementById('gambar');
    var cropModal = document.getElementById('cropModal');
    var cropImage = document.getElementById('cropImage');
    var cropCancel = document.getElementById('cropCancel');
    var cropConfirm = document.getElementById('cropConfirm');
    var previewContainer = document.createElement('div');
    previewContainer.className = 'preview-container';
    input.parentNode.appendChild(previewContainer);
    
    var files = []; // Store files here
    var currentIndex = 0;
    var cropper = null;
    
    // Create an empty Data Transfer object for storing cropped files
    var dt = new DataTransfer();
    
    // Show crop modal for current file
    function showCropModal(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // Display image in modal
            cropImage.src = e.target.result;
            cropModal.style.display = 'block';
            
            // Initialize cropper after image is loaded
            cropImage.onload = function() {
                if (cropper) {
                    cropper.destroy();
                }
                
                // Create new cropper instance with interactive controls
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1, // 1:1 square crop
                    viewMode: 1,    // restrict crop box to not exceed the size of the canvas
                    dragMode: 'move', // Allow moving the image
                    guides: true,   // Show grid lines 
                    center: true,   // Show center indicator
                    background: false, // Don't show grey background
                    autoCropArea: 0.8, // Define default crop area size (80% of the image)
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: true
                });
            };
        };
        reader.readAsDataURL(file);
    }
    
    // Process the current file
    function processCurrentFile() {
        if (currentIndex < files.length) {
            showCropModal(files[currentIndex]);
        } else {
            // All files processed, update the input
            input.files = dt.files;
        }
    }
    
    // File selection handler
    input.addEventListener('change', function(e) {
        // Clear previous state
        while(previewContainer.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }
        
        if (this.files.length === 0) return;
        
        // Reset variables
        files = Array.from(this.files);
        currentIndex = 0;
        dt = new DataTransfer();
        
        // Start processing the first file
        processCurrentFile();
    });
    
    // Cancel button handler
    cropCancel.addEventListener('click', function() {
        cropModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        input.value = '';
        files = [];
        currentIndex = 0;
        dt = new DataTransfer();
        while(previewContainer.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }
    });
    
    // Confirm button handler
    cropConfirm.addEventListener('click', function() {
        if (!cropper) return;
        
        // Get cropped canvas
        cropper.getCroppedCanvas({
            width: 600,   // output size
            height: 600,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        }).toBlob(function(blob) {
            // Create a new file from the blob
            var croppedFile = new File([blob], files[currentIndex].name, {
                type: 'image/jpeg',
                lastModified: new Date().getTime()
            });
            
            // Add to data transfer object
            dt.items.add(croppedFile);
            
            // Create preview container with delete button
            var previewItem = document.createElement('div');
            previewItem.className = 'relative group sortable-item';
            previewItem.style.display = 'inline-block';
            
            // Create preview image
            var preview = document.createElement('img');
            preview.src = URL.createObjectURL(blob);
            preview.className = 'preview-image';
            
            // Create delete button
            var deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.title = 'Hapus gambar';
            deleteBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
            
            // Add click event to delete button
            deleteBtn.addEventListener('click', function() {
                const index = Array.from(previewContainer.children).indexOf(previewItem);
                if (index !== -1) {
                    // Create new DataTransfer to rebuild file list without this item
                    const newDt = new DataTransfer();
                    for (let i = 0; i < dt.files.length; i++) {
                        if (i !== index) {
                            newDt.items.add(dt.files[i]);
                        }
                    }
                    dt = newDt;
                    input.files = dt.files;
                    
                    // Remove visual preview
                    previewItem.remove();
                }
            });
            
            // Append elements to preview item
            previewItem.appendChild(preview);
            previewItem.appendChild(deleteBtn);
            
            // Add to preview container
            previewContainer.appendChild(previewItem);
            
            // Hide modal and cleanup
            cropModal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            // Move to next file
            currentIndex++;
            processCurrentFile();
        }, 'image/jpeg', 0.95);
    });
});
</script>

<!-- Price formatting and validation script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaTermurahDisplay = document.getElementById('harga_termurah_display');
    const hargaTermahalDisplay = document.getElementById('harga_termahal_display');
    const hargaTermurah = document.getElementById('harga_termurah');
    const hargaTermahal = document.getElementById('harga_termahal');
    const hargaError = document.getElementById('harga-error');
    const form = document.querySelector('form');

    // Format number with thousands separator
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Remove thousands separator and get numeric value
    function unformatNumber(str) {
        return parseInt(str.replace(/\./g, '')) || 0;
    }

    // Format input on keyup
    function handleNumberInput(displayInput, hiddenInput) {
        displayInput.addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, ''); // Only allow digits
            if (value) {
                this.value = formatNumber(value);
                hiddenInput.value = value;
            } else {
                this.value = '';
                hiddenInput.value = '';
            }
            validatePrices();
        });
    }

    // Validate that harga_termahal >= harga_termurah
    function validatePrices() {
        const termurah = unformatNumber(hargaTermurahDisplay.value);
        const termahal = unformatNumber(hargaTermahalDisplay.value);

        if (termurah > 0 && termahal > 0 && termahal < termurah) {
            hargaError.textContent = 'Harga termahal harus lebih besar atau sama dengan harga termurah';
            hargaError.style.display = 'block';
            hargaTermahalDisplay.classList.add('border-red-500');
            hargaTermurahDisplay.classList.add('border-red-500');
            return false;
        } else {
            hargaError.style.display = 'none';
            hargaTermahalDisplay.classList.remove('border-red-500');
            hargaTermurahDisplay.classList.remove('border-red-500');
            return true;
        }
    }

    // Initialize formatting
    handleNumberInput(hargaTermurahDisplay, hargaTermurah);
    handleNumberInput(hargaTermahalDisplay, hargaTermahal);

    // Prevent form submission if validation fails
    form.addEventListener('submit', function(e) {
        if (!validatePrices()) {
            e.preventDefault();
            hargaError.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>
@endpush