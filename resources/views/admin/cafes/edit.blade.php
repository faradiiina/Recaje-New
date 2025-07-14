@extends('layouts.admin.app')

@section('title', 'Edit Cafe')

@section('styles')
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
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 15px;
            max-width: 900px;
        }
        
        .preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid #ddd;
        }

        /* Sortable Styles */
        .sortable-item {
            cursor: move !important;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            border: 2px solid transparent;
            overflow: hidden;
            height: 150px; 
            border-radius: 8px;
            margin: 0;
            display: block;
        }
        
        .sortable-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .sortable-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-color: #3490dc;
        }
        
        .sortable-ghost {
            opacity: 0.5;
            background-color: #d1e7ff;
            border: 2px dashed #3490dc;
        }
        
        .sortable-chosen {
            box-shadow: 0 10px 20px rgba(0,0,0,0.25);
            transform: scale(1.02);
            z-index: 10;
        }
        
        .handle {
            cursor: grab !important;
        }
        
        .handle:active {
            cursor: grabbing !important;
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

        .drag-hint {
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #3490dc;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 15;
            opacity: 0;
            transition: opacity 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        .highlight-moved {
            animation: highlight-pulse 1s ease-in-out;
        }
        
        @keyframes highlight-pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 144, 220, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(52, 144, 220, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 144, 220, 0); }
        }
    </style>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Cafe: {{ $cafe->nama }}</h1>
            <a href="{{ route('admin.cafes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 cursor-pointer">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.cafes.update', $cafe) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Nama Cafe dan Peta -->
                <div class="space-y-6">
                    <div class="field-spacer">
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NAMA CAFE</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $cafe->nama) }}" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>

                    <div class="field-spacer">
                        <label for="area" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">AREA</label>
                        <div class="flex flex-wrap gap-4">
                            @php
                                $areas = $cafe->area ? explode(', ', $cafe->area) : [];
                            @endphp
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_indoor" value="Indoor" {{ in_array('Indoor', $areas) || (is_array(old('area')) && in_array('Indoor', old('area'))) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                <label for="area_indoor" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Indoor</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_semi_outdoor" value="Semi Outdoor" {{ in_array('Semi Outdoor', $areas) || (is_array(old('area')) && in_array('Semi Outdoor', old('area'))) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                <label for="area_semi_outdoor" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Semi Outdoor</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="area[]" id="area_outdoor" value="Outdoor" {{ in_array('Outdoor', $areas) || (is_array(old('area')) && in_array('Outdoor', old('area'))) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 dark:text-blue-500 rounded border-gray-300 focus:ring-blue-500">
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
                        <input type="text" name="koordinat" id="koordinat" value="{{ $cafe->latitude && $cafe->longitude ? $cafe->latitude . ', ' . $cafe->longitude : '' }}" placeholder="Contoh: -8.160591, 113.721279" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        
                        <!-- Hidden fields for latitude and longitude - tidak menggunakan old() -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ $cafe->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $cafe->longitude }}">
                    </div>

                    <div class="field-spacer">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">RENTANG HARGA</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harga_termurah" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Harga Termurah (Rp)</label>
                                <input type="text" name="harga_termurah_display" id="harga_termurah_display" value="{{ old('harga_termurah', $cafe->harga_termurah) ? number_format(old('harga_termurah', $cafe->harga_termurah), 0, ',', '.') : '' }}" placeholder="0" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <input type="hidden" name="harga_termurah" id="harga_termurah" value="{{ old('harga_termurah', $cafe->harga_termurah) }}">
                            </div>
                            <div>
                                <label for="harga_termahal" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Harga Termahal (Rp)</label>
                                <input type="text" name="harga_termahal_display" id="harga_termahal_display" value="{{ old('harga_termahal', $cafe->harga_termahal) ? number_format(old('harga_termahal', $cafe->harga_termahal), 0, ',', '.') : '' }}" placeholder="0" style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <input type="hidden" name="harga_termahal" id="harga_termahal" value="{{ old('harga_termahal', $cafe->harga_termahal) }}">
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
                                    @php
                                        $selected = false;
                                        if (isset($cafeRatings[$category->id]) && $cafeRatings[$category->id] == $subcategory->id) {
                                            $selected = true;
                                        } elseif (old('category_ratings.'.$category->id) == $subcategory->id) {
                                            $selected = true;
                                        }
                                    @endphp
                                    <option value="{{ $subcategory->id }}" {{ $selected ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

                    <div class="field-spacer">
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">GAMBAR CAFE (OPSIONAL)</label>
                        <input type="file" name="gambar[]" id="gambar" multiple style="min-height: 48px; padding-top: 12px; padding-bottom: 12px; padding-left: 8px;" class="w-full py-3 pl-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <p class="text-sm text-gray-500 mt-1">Anda dapat memilih lebih dari satu gambar. Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran per file: 2MB</p>
                        
                        @if($cafe->images->count() > 0)
                            <div class="mt-4">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">Gambar saat ini (geser untuk mengatur urutan):</p>
                                <div id="sortable-images" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-w-[900px]">
                                    @foreach($cafe->images as $image)
                                        <div class="relative group sortable-item" data-id="{{ $image->id }}" style="width: 100%; height: 150px;">
                                            <!-- Handle untuk drag -->
                                            <div class="handle absolute top-0 left-0 p-1 text-white bg-blue-600 rounded-br z-20">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11z"/>
                                                </svg>
                                            </div>
                                            <!-- Gambar -->
                                            <img src="{{ asset($image->image_path) }}" alt="{{ $cafe->nama }}" class="w-full h-full object-cover rounded">
                                            
                                            <!-- Overlay text saat hover -->
                                            <div class="sortable-indicator flex items-center justify-center">
                                                <div class="bg-blue-600 text-white p-1 rounded text-xs">
                                                    Geser Yang Dipojok
                                                </div>
                                            </div>
                                            
                                            <!-- Tombol hapus -->
                                            <button type="button" class="delete-btn delete-image" data-image-id="{{ $image->id }}" title="Hapus gambar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="image-controls" class="mt-3">
                                    <button type="button" id="save-order" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Simpan Urutan
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Container untuk gambar saat tidak ada gambar tersimpan -->
                            <div class="mt-4 hidden">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">Gambar Cafe:</p>
                                <div id="sortable-images" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-w-[900px]">
                                    <!-- Gambar baru akan ditambahkan di sini -->
                                </div>
                                <div id="image-controls" class="mt-3" style="display: none;">
                                    <button type="button" id="save-order" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Simpan Urutan
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
                    Perbarui
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

    <!-- Form Hapus Gambar -->
    <form id="deleteImageForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Preview container untuk gambar yang diupload -->
    <div class="preview-container mt-4 flex flex-wrap gap-2"></div>
@endsection

@push('scripts')
<!-- Map scripts -->
<script>
    // Tetapkan nilai koordinat langsung sebagai variabel JS untuk menghindari cache
    const DB_LATITUDE = "{{ $cafe->latitude ?: 'null' }}";
    const DB_LONGITUDE = "{{ $cafe->longitude ?: 'null' }}";
    const TIMESTAMP = "{{ time() }}"; // Tambah cache buster yang lebih agresif
    
    // Fungsi untuk memaksa refresh halaman jika parameter masih dari cache
    function forceRefreshIfNeeded() {
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('ts') || parseInt(urlParams.get('ts')) < {{ time() - 10 }}) {
            // Redirect ke halaman yang sama dengan timestamp baru
            window.location.href = "{{ route('admin.cafes.edit', $cafe->id) }}?ts={{ time() }}";
            return true;
        }
        return false;
    }
    
    // Paksa refresh halaman jika tidak ada parameter timestamp baru
    if (!forceRefreshIfNeeded()) {
        $(document).ready(function() {
            console.log('DB COORDINATES:', DB_LATITUDE, DB_LONGITUDE);
            
            // Paksa update nilai input untuk mengatasi cache browser
            document.getElementById('latitude').value = DB_LATITUDE;
            document.getElementById('longitude').value = DB_LONGITUDE;
            document.getElementById('koordinat').value = (DB_LATITUDE && DB_LONGITUDE) ? 
                `${DB_LATITUDE}, ${DB_LONGITUDE}` : '';
            
            console.log('Raw DB values - Latitude:', DB_LATITUDE, 'Longitude:', DB_LONGITUDE);
            
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

                    // PENTING: Gunakan nilai DB_LATITUDE dan DB_LONGITUDE langsung, bukan dari input
                    const hasCoordinates = DB_LATITUDE && DB_LONGITUDE && 
                                          !isNaN(parseFloat(DB_LATITUDE)) && 
                                          !isNaN(parseFloat(DB_LONGITUDE));
                    
                    console.log('DB coordinates check:', DB_LATITUDE, DB_LONGITUDE, 'hasCoordinates:', hasCoordinates);
                    
                    // Default ke pusat Jember jika tidak ada koordinat tersimpan
                    const defaultLat = -8.159765315131203;
                    const defaultLng = 113.72309609838182;
                    
                    // Koordinat yang akan digunakan (tersimpan atau default)
                    const centerLat = hasCoordinates ? parseFloat(DB_LATITUDE) : defaultLat;
                    const centerLng = hasCoordinates ? parseFloat(DB_LONGITUDE) : defaultLng;
                    
                    console.log('Creating map with direct DB coordinates:', centerLat, centerLng);
                    
                    const map = L.map('map', {
                        center: [centerLat, centerLng],
                        zoom: hasCoordinates ? 15 : 13, // Zoom lebih dekat jika ada koordinat tersimpan
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

                    // Tambahkan marker jika ada koordinat tersimpan - gunakan nilai DB langsung
                    if (hasCoordinates) {
                        marker = L.marker([parseFloat(DB_LATITUDE), parseFloat(DB_LONGITUDE)]).addTo(map);
                        console.log('Added marker at DB coordinates:', DB_LATITUDE, DB_LONGITUDE);
                    }

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

                    // Event untuk update peta saat koordinat diubah secara manual
                    const koordinatInput = document.getElementById('koordinat');
                    koordinatInput.addEventListener('change', function() {
                        const koordinatValue = this.value.trim();
                        const koordinatArray = koordinatValue.split(',');
                        
                        if (koordinatArray.length === 2) {
                            const lat = parseFloat(koordinatArray[0].trim());
                            const lng = parseFloat(koordinatArray[1].trim());
                            
                            if (!isNaN(lat) && !isNaN(lng)) {
                                // Update hidden input values
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lng;
                                
                                // Update marker and map center
                                if (marker) {
                                    map.removeLayer(marker);
                                }
                                marker = L.marker([lat, lng]).addTo(map);
                                map.setView([lat, lng], 15);
                                
                                console.log('Map updated from manual koordinat input:', lat, lng);
                            } else {
                                console.error('Invalid koordinat format, expected numbers but got:', koordinatArray);
                            }
                        } else {
                            console.error('Invalid koordinat format, expected "lat, lng" but got:', koordinatValue);
                        }
                    });
                    
                    // Juga tambahkan event untuk field individu (latitude/longitude)
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    
                    function updateMapFromIndividualFields() {
                        const lat = parseFloat(latInput.value.trim());
                        const lng = parseFloat(lngInput.value.trim());
                        
                        if (!isNaN(lat) && !isNaN(lng)) {
                            // Update koordinat input
                            koordinatInput.value = `${lat}, ${lng}`;
                            
                            // Update marker and map center
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            marker = L.marker([lat, lng]).addTo(map);
                            map.setView([lat, lng], 15);
                            
                            console.log('Map updated from individual lat/lng inputs:', lat, lng);
                        }
                    }
                    
                    latInput.addEventListener('change', updateMapFromIndividualFields);
                    lngInput.addEventListener('change', updateMapFromIndividualFields);

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
            
            // Kode untuk menghapus gambar
            $('.delete-image').click(function() {
                const imageId = $(this).data('image-id');
                const imageContainer = $(this).closest('.relative');
                
                if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                    // Gunakan form yang sudah ada untuk mengirim request DELETE
                    const form = $('#deleteImageForm');
                    form.attr('action', "{{ route('admin.cafes.delete-image', ':id') }}".replace(':id', imageId));
                    
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                imageContainer.fadeOut(300, function() {
                                    $(this).remove();
                                });
                            } else {
                                alert('Gagal menghapus gambar: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan saat menghapus gambar. Silakan coba lagi.');
                            console.error('Error:', xhr);
                        }
                    });
                }
            });
        });
    }
</script>

<!-- Cropper Interactive -->
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
console.log('Latitude dari input: ', document.getElementById('latitude').getAttribute('value'));
console.log('Longitude dari input: ', document.getElementById('longitude').getAttribute('value'));

document.addEventListener('DOMContentLoaded', function() {
    // Variables
    var input = document.getElementById('gambar');
    var cropModal = document.getElementById('cropModal');
    var cropImage = document.getElementById('cropImage');
    var cropCancel = document.getElementById('cropCancel');
    var cropConfirm = document.getElementById('cropConfirm');
    var sortableContainer = document.getElementById('sortable-images');
    var saveOrderBtn = document.getElementById('save-order');
    
    // Sembunyikan tombol simpan urutan karena kita simpan otomatis
    if (saveOrderBtn) {
        saveOrderBtn.style.display = 'none';
    }
    
    console.log('DOM loaded, checking for sortable elements');
    
    // Inisialisasi Sortable
    if (sortableContainer) {
        console.log('Initializing Sortable on container:', sortableContainer);
        window.cafeSortable = new Sortable(sortableContainer, {
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            forceFallback: true,
            onStart: function(evt) {
                console.log('Drag started:', evt.oldIndex);
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function(evt) {
                console.log('Drag ended. Old index:', evt.oldIndex, 'New index:', evt.newIndex);
                document.body.style.cursor = 'auto';
                
                // Highlight the moved item
                if (evt.oldIndex !== evt.newIndex) {
                    evt.item.style.backgroundColor = '#d1e7ff';
                    setTimeout(function() {
                        evt.item.style.backgroundColor = '';
                    }, 1000);
                    
                    // Otomatis simpan urutan setelah drag selesai
                    saveImageOrder();
                }
            }
        });
        
        // Show sortable container dan parent jika tersembunyi
        var sortableParent = sortableContainer.parentElement;
        if (sortableParent.classList.contains('hidden')) {
            sortableParent.classList.remove('hidden');
        }
    } else {
        console.warn('Sortable container not found!');
    }
    
    // Create preview container if needed
    var previewContainer = document.querySelector('.preview-container');
    if (!previewContainer) {
        previewContainer = document.createElement('div');
        previewContainer.className = 'preview-container mt-4 flex flex-wrap gap-2';
        input.parentNode.appendChild(previewContainer);
    }
    
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
            
            // Make sure save button is visible if we have images
            if (dt.files.length > 0) {
                document.getElementById('image-controls').style.display = 'block';
            }
        }
    }
    
    // Generate temporary ID for new images
    function generateTempId() {
        return 'temp_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    }
    
    // Add cropped image to sortable container
    function addImageToSortableContainer(blob, fileName) {
        // Create a URL for the blob
        var imageUrl = URL.createObjectURL(blob);
        
        // Create the sortable item structure
        var sortableItem = document.createElement('div');
        sortableItem.className = 'relative group sortable-item';
        sortableItem.setAttribute('data-id', generateTempId());
        sortableItem.style.width = '100%';
        sortableItem.style.height = '150px';
        
        // Create handle
        var handle = document.createElement('div');
        handle.className = 'handle absolute top-0 left-0 p-1 text-white bg-blue-600 rounded-br z-20';
        handle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11z"/></svg>';
        
        // Create image
        var img = document.createElement('img');
        img.src = imageUrl;
        img.alt = fileName;
        img.className = 'w-full h-full object-cover rounded';
        
        // Create sortable indicator
        var indicator = document.createElement('div');
        indicator.className = 'sortable-indicator flex items-center justify-center';
        indicator.innerHTML = '<div class="bg-blue-600 text-white p-1 rounded text-xs">Geser</div>';
        
        // Create delete button with icon
        var deleteBtn = document.createElement('button');
        deleteBtn.className = 'delete-btn temp-delete-image';
        deleteBtn.title = 'Hapus gambar';
        deleteBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
        
        // Assemble the sortable item
        sortableItem.appendChild(handle);
        sortableItem.appendChild(img);
        sortableItem.appendChild(indicator);
        sortableItem.appendChild(deleteBtn);
        
        // Add to sortable container
        sortableContainer.appendChild(sortableItem);
        
        // Tampilkan container jika tersembunyi
        if (sortableContainer.parentElement.classList.contains('hidden')) {
            sortableContainer.parentElement.classList.remove('hidden');
        }
        
        // Temporary delete button handler
        deleteBtn.addEventListener('click', function() {
            if (confirm('Hapus gambar ini dari daftar upload?')) {
                sortableItem.remove();
            }
        });
        
        // Refresh sortable untuk mengenali item baru
        if (window.cafeSortable) {
            window.cafeSortable.option("disabled", false); // Pastikan sortable enabled
        }
    }
    
    // File selection handler
    input.addEventListener('change', function(e) {
        if (this.files.length === 0) return;
        
        // Reset variables
        files = Array.from(this.files);
        currentIndex = 0;
        dt = new DataTransfer();
        
        // Clear preview container
        while(previewContainer.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }
        
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
            
            // Create preview
            var preview = document.createElement('img');
            preview.src = URL.createObjectURL(blob);
            preview.className = 'preview-image';
            preview.alt = files[currentIndex].name;
            previewContainer.appendChild(preview);
            
            // Add to sortable container as well
            addImageToSortableContainer(blob, files[currentIndex].name);
            
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
    
    // Event listener untuk tombol hapus gambar
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-image')) {
            const deleteBtn = e.target.closest('.delete-image');
            const imageId = deleteBtn.getAttribute('data-image-id');
            const imageContainer = deleteBtn.closest('.sortable-item');
            
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                // Show loading state
                deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                deleteBtn.disabled = true;
                
                // Gunakan form yang sudah ada untuk mengirim request DELETE
                const form = document.getElementById('deleteImageForm');
                form.action = "{{ route('admin.cafes.delete-image', ':id') }}".replace(':id', imageId);
                
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fade out effect before removing
                        imageContainer.style.transition = 'all 0.3s';
                        imageContainer.style.opacity = '0';
                        imageContainer.style.transform = 'scale(0.8)';
                        
                        setTimeout(() => {
                            imageContainer.remove();
                            
                            // Show success message
                            const alertMessage = document.createElement('div');
                            alertMessage.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-2 rounded text-sm';
                            alertMessage.innerHTML = 'Gambar berhasil dihapus';
                            
                            sortableContainer.parentElement.insertBefore(alertMessage, sortableContainer);
                            
                            setTimeout(() => {
                                alertMessage.style.opacity = '0';
                                alertMessage.style.transition = 'opacity 1s';
                                
                                setTimeout(() => {
                                    alertMessage.remove();
                                }, 1000);
                            }, 2000);
                        }, 300);
                    } else {
                        alert('Gagal menghapus gambar: ' + data.message);
                        // Reset button
                        deleteBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
                        deleteBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus gambar. Silakan coba lagi.');
                    // Reset button
                    deleteBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
                    deleteBtn.disabled = false;
                });
            }
        }
    });
    
    // Fungsi untuk menyimpan urutan gambar
    function saveImageOrder() {
        var imageIds = [];
        var items = sortableContainer.querySelectorAll('.sortable-item');
        
        // Collect all image IDs in current order
        items.forEach(function(item) {
            imageIds.push(item.getAttribute('data-id'));
        });
        
        console.log('Saving image order:', imageIds);
        
        // Animasi loading pada container gambar
        sortableContainer.classList.add('opacity-50');
        
        // Buat data yang akan dikirim
        var postData = {
            cafe_id: "{{ $cafe->id }}",
            image_ids: imageIds
        };
        
        console.log('Sending data:', postData);
        
        // Send the order to the server
        fetch('{{ route("admin.cafes.update-image-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(postData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Tampilkan animasi sukses sebentar
                sortableContainer.classList.remove('opacity-50');
                sortableContainer.classList.add('border-green-500');
                
                setTimeout(function() {
                    sortableContainer.classList.remove('border-green-500');
                }, 1000);
            } else {
                alert('Gagal menyimpan urutan: ' + data.message);
                sortableContainer.classList.remove('opacity-50');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan urutan. Silakan coba lagi.');
            sortableContainer.classList.remove('opacity-50');
        });
    }
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
    if (hargaTermurahDisplay && hargaTermurah) {
        handleNumberInput(hargaTermurahDisplay, hargaTermurah);
    }
    if (hargaTermahalDisplay && hargaTermahal) {
        handleNumberInput(hargaTermahalDisplay, hargaTermahal);
    }

    // Prevent form submission if validation fails
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validatePrices()) {
                e.preventDefault();
                hargaError.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
</script>
@endpush