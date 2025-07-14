@extends('layouts.app')

@section('title', ' - Pencarian Café')

@section('content')
    <!-- Load JS eksternal dengan variabel global -->
    <script src="{{ asset('js/cafe-search.js') }}" 
        data-has-search-results="{{ request()->has('weight') ? 'true' : 'false' }}"
        data-current-step="{{ $currentStep }}">
    </script>
    
    <!-- CSS untuk styling slider -->
    <style>
        /* Styling dasar untuk slider */
        .importance-slider {
            height: 8px;
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            cursor: pointer;
        }
        
        /* Styling untuk track (garis) slider */
        .importance-slider::-webkit-slider-runnable-track {
            height: 8px;
            background: #e5e7eb;
            border-radius: 0.5rem;
        }
        .importance-slider::-moz-range-track {
            height: 8px;
            background: #e5e7eb;
            border-radius: 0.5rem;
        }
        
        /* Styling untuk thumb (knob/pointer) slider - diperbesar */
        .importance-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            height: 26px;
            width: 26px;
            background: #3b82f6; /* Warna biru (blue-500) */
            border-radius: 50%;
            border: 3px solid white;
            margin-top: -9px; /* Untuk memposisikan di tengah track */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background 0.2s, transform 0.1s;
        }
        .importance-slider::-moz-range-thumb {
            height: 26px;
            width: 26px;
            background: #3b82f6;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background 0.2s, transform 0.1s;
        }
        
        /* Efek hover pada thumb */
        .importance-slider:hover::-webkit-slider-thumb {
            background: #2563eb; /* Warna blue-600 */
            transform: scale(1.1);
        }
        .importance-slider:hover::-moz-range-thumb {
            background: #2563eb;
            transform: scale(1.1);
        }
        
        /* Efek focus pada slider */
        .importance-slider:focus {
            outline: none;
        }
        .importance-slider:focus::-webkit-slider-thumb {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        .importance-slider:focus::-moz-range-thumb {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        
        /* Styling untuk mode gelap */
        .dark .importance-slider::-webkit-slider-runnable-track {
            background: #374151;
        }
        .dark .importance-slider::-moz-range-track {
            background: #374151;
        }
        
        /* Styling tambahan untuk slider aktif saat drag */
        .active-slider::-webkit-slider-thumb {
            transform: scale(1.15);
            background: #1d4ed8; /* Warna blue-700 */
        }
        .active-slider::-moz-range-thumb {
            transform: scale(1.15);
            background: #1d4ed8;
        }
        
        /* Styling untuk checkbox area */
        .area-checkbox {
            display: none;
        }
        
        .area-checkbox-label {
            display: inline-block;
            padding: 8px 15px;
            margin-right: 8px;
            margin-bottom: 8px;
            border-radius: 30px;
            border: 1px solid #e5e7eb;
            background-color: #111827;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .area-checkbox:checked + .area-checkbox-label {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .dark .area-checkbox-label {
            border-color: #4b5563;
            background-color: #1f2937;
            color: #e5e7eb;
        }
        
        .dark .area-checkbox:checked + .area-checkbox-label {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
    </style>
    
    <!-- Script untuk membuat slider lebih responsif (bisa diklik di semua area) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan semua elemen slider
            const sliders = document.querySelectorAll('.importance-slider');
            let isDragging = false;
            let currentSlider = null;
            
            // Fungsi untuk menghitung dan menetapkan nilai berdasarkan posisi mouse
            function setSliderValueFromPosition(slider, clientX) {
                const rect = slider.getBoundingClientRect();
                let position = (clientX - rect.left) / rect.width;
                
                // Batasi posisi dalam range 0-1
                position = Math.max(0, Math.min(1, position));
                
                // Konversi ke nilai slider (min-max)
                const min = parseInt(slider.min) || 0;
                const max = parseInt(slider.max) || 10;
                const value = Math.round(min + position * (max - min));
                
                // Set nilai slider
                slider.value = value;
                
                // Trigger event input untuk memperbarui UI
                const event = new Event('input', { bubbles: true });
                slider.dispatchEvent(event);
                
                // Panggil fungsi updateSliderValue yang sudah ada
                updateSliderValue(slider.getAttribute('data-id'), value);
            }
            
            // Tambahkan event listener untuk setiap slider
            sliders.forEach(function(slider) {
                // Event click pada container slider
                slider.addEventListener('click', function(e) {
                    // Hanya proses jika bukan klik pada thumb
                    if (e.target === slider) {
                        setSliderValueFromPosition(slider, e.clientX);
                    }
                });
                
                // Event untuk memulai drag
                slider.addEventListener('mousedown', function(e) {
                    // Tandai bahwa kita sedang melakukan drag
                    isDragging = true;
                    currentSlider = slider;
                    
                    // Segera perbarui nilai slider saat ditekan
                    setSliderValueFromPosition(slider, e.clientX);
                    
                    // Tambahkan class untuk menandai slider aktif
                    slider.classList.add('active-slider');
                });
            });
            
            // Listener untuk gerakan mouse di seluruh dokumen
            document.addEventListener('mousemove', function(e) {
                if (isDragging && currentSlider) {
                    setSliderValueFromPosition(currentSlider, e.clientX);
                    
                    // Mencegah pemilihan teks saat drag
                    e.preventDefault();
                }
            });
            
            // Listener untuk menghentikan drag
            document.addEventListener('mouseup', function() {
                if (currentSlider) {
                    currentSlider.classList.remove('active-slider');
                }
                isDragging = false;
                currentSlider = null;
            });
            
            // Menangani ketika mouse meninggalkan jendela
            document.addEventListener('mouseleave', function() {
                if (currentSlider) {
                    currentSlider.classList.remove('active-slider');
                }
                isDragging = false;
                currentSlider = null;
            });
        });
        
        // Fungsi untuk mengupdate nilai slider dan menghitung bobot
        function updateSliderValue(categoryId, value) {
            const slider = document.getElementById('importance_' + categoryId);
            const valueDisplay = document.getElementById('importance_value_' + categoryId);
            const weightDisplay = document.getElementById('weight_display_' + categoryId);
            const hiddenWeight = document.getElementById('hidden_weight_' + categoryId);
            
            // Update tampilan nilai slider
            const valueLabels = {
                0: 'Tidak Penting (0)',
                1: 'Sangat Rendah (1)', 
                2: 'Rendah (2)',
                3: 'Agak Rendah (3)',
                4: 'Kurang (4)',
                5: 'Sedang (5)',
                6: 'Agak Penting (6)',
                7: 'Penting (7)',
                8: 'Sangat Penting (8)',
                9: 'Krusial (9)',
                10: 'Mutlak Perlu (10)'
            };
            
            if (valueDisplay) {
                valueDisplay.textContent = valueLabels[value] || `Level ${value}`;
            }
            
            // Konversi nilai slider ke persentase dengan pembulatan yang presisi
            const allSliders = document.querySelectorAll('.importance-slider');
            let rawValues = {};
            let totalRawValue = 0;
            
            // Langkah 1: Ambil semua nilai slider
            allSliders.forEach(function(s) {
                const id = s.getAttribute('data-id');
                const val = parseInt(s.value) || 0;
                rawValues[id] = val;
                totalRawValue += val;
            });
            
            // Langkah 2: Normalisasi ke persentase dengan pembulatan yang konsisten
            let normalizedPercentages = {};
            
            if (totalRawValue > 0) {
                // Hitung persentase eksak untuk setiap kategori
                const categoryIds = Object.keys(rawValues);
                const exactPercentages = {};
                
                // Hitung persentase eksak
                categoryIds.forEach(function(id) {
                    exactPercentages[id] = (rawValues[id] / totalRawValue) * 100;
                });
                
                // Algoritma pembulatan untuk memastikan total = 100%
                let roundedPercentages = {};
                let runningTotal = 0;
                
                // Buat array dengan informasi kategori dan desimal
                const categoryData = categoryIds.map(function(id) {
                    const exact = exactPercentages[id];
                    const floored = Math.floor(exact);
                    const decimal = exact - floored;
                    return {
                        id: id,
                        exact: exact,
                        floored: floored,
                        decimal: decimal
                    };
                });
                
                // Bulatkan ke bawah terlebih dahulu
                categoryData.forEach(function(data) {
                    roundedPercentages[data.id] = data.floored;
                    runningTotal += data.floored;
                });
                
                // Urutkan berdasarkan bagian desimal tertinggi
                categoryData.sort(function(a, b) {
                    return b.decimal - a.decimal;
                });
                
                // Distribusikan sisa persentase untuk mencapai total 100%
                let remainder = 100 - runningTotal;
                let index = 0;
                while (remainder > 0 && index < categoryData.length) {
                    roundedPercentages[categoryData[index].id]++;
                    remainder--;
                    index++;
                }
                
                normalizedPercentages = roundedPercentages;
            } else {
                // Jika semua slider 0, bagi rata
                const sliderCount = allSliders.length;
                const basePercentage = Math.floor(100 / sliderCount);
                const remainder = 100 - (basePercentage * sliderCount);
                
                let index = 0;
                allSliders.forEach(function(s) {
                    const id = s.getAttribute('data-id');
                    normalizedPercentages[id] = basePercentage + (index < remainder ? 1 : 0);
                    index++;
                });
            }
            
            // Langkah 3: Update tampilan dan hidden input untuk semua slider
            allSliders.forEach(function(s) {
                const id = s.getAttribute('data-id');
                const val = parseInt(s.value) || 0;
                const normalizedPercentage = normalizedPercentages[id];
                const weightDisplayElement = document.getElementById('weight_display_' + id);
                const hiddenWeightElement = document.getElementById('hidden_weight_' + id);
                
                // Update tampilan bobot
                if (weightDisplayElement) {
                    if (val === 0) {
                        if (totalRawValue === 0) {
                            // Jika semua slider 0, tampilkan bobot merata
                            weightDisplayElement.textContent = normalizedPercentage + '% (Merata)';
                            weightDisplayElement.style.color = '#F59E0B'; // Yellow-500
                        } else {
                            // Jika slider ini 0 tapi ada yang lain > 0
                            weightDisplayElement.textContent = '0% (Diabaikan)';
                            weightDisplayElement.style.color = '#9CA3AF'; // Gray-400
                        }
                    } else {
                        weightDisplayElement.textContent = normalizedPercentage + '%';
                        weightDisplayElement.style.color = '#059669'; // Green-600
                    }
                }
                
                // Update hidden input dengan persentase yang sudah dinormalisasi
                if (hiddenWeightElement) {
                    hiddenWeightElement.value = normalizedPercentage.toFixed(2);
                }
            });
            
            // Verifikasi total persentase (untuk debugging)
            const totalFinal = Object.values(normalizedPercentages).reduce((sum, val) => sum + val, 0);
            console.log('Total persentase:', totalFinal, '% - Kategori:', Object.keys(normalizedPercentages).map(id => {
                const category = document.querySelector(`[data-id="${id}"]`).closest('.rounded-lg').querySelector('label').textContent;
                return category + ': ' + normalizedPercentages[id] + '%';
            }).join(', '));
            
            // Tampilkan peringatan jika semua slider di-set ke 0
            const warningDiv = document.getElementById('zero-weight-warning');
            if (totalRawValue === 0) {
                if (!warningDiv) {
                    const warning = document.createElement('div');
                    warning.id = 'zero-weight-warning';
                    warning.className = 'mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg text-sm';
                    warning.innerHTML = '<strong>Peringatan:</strong> Semua kriteria memiliki bobot 0. Hasil pencarian akan menggunakan bobot merata untuk semua kriteria.';
                    
                    const firstSliderContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.gap-6');
                    if (firstSliderContainer && firstSliderContainer.parentNode) {
                        firstSliderContainer.parentNode.insertBefore(warning, firstSliderContainer.nextSibling);
                    }
                }
            } else {
                if (warningDiv) {
                    warningDiv.remove();
                }
            }
        }
        
        // Inisialisasi nilai slider saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const allSliders = document.querySelectorAll('.importance-slider');
            
            // Jika ada request weight (hasil pencarian), gunakan itu untuk restore slider
            const hasSearchResults = {{ request()->has('weight') ? 'true' : 'false' }};
            const requestWeights = @json(request('weight') ?? []);
            
            if (hasSearchResults && Object.keys(requestWeights).length > 0) {
                // Mode restore: set slider berdasarkan bobot yang ada
                // JANGAN jalankan updateSliderValue karena akan mengacaukan bobot yang sudah benar
                allSliders.forEach(function(slider) {
                    const categoryId = slider.getAttribute('data-id');
                    const weightFromRequest = requestWeights[categoryId];
                    
                    if (weightFromRequest !== undefined) {
                        // Estimasi nilai slider dari persentase
                        // Karena normalisasi kompleks, kita perlu estimasi terbalik
                        const percentage = parseFloat(weightFromRequest);
                        let estimatedSliderValue;
                        
                        if (percentage === 0) {
                            estimatedSliderValue = 0;
                        } else if (percentage >= 90) {
                            estimatedSliderValue = 10;
                        } else if (percentage >= 70) {
                            estimatedSliderValue = 8;
                        } else if (percentage >= 50) {
                            estimatedSliderValue = 6;
                        } else if (percentage >= 30) {
                            estimatedSliderValue = 4;
                        } else if (percentage >= 10) {
                            estimatedSliderValue = 2;
                        } else if (percentage > 0) {
                            estimatedSliderValue = 1;
                        } else {
                            estimatedSliderValue = 0;
                        }
                        
                        // Set nilai slider
                        slider.value = estimatedSliderValue;
                        
                        // Update hidden input dengan bobot asli dari request (TIDAK memanggil updateSliderValue yang akan normalize ulang)
                        const hiddenInput = document.getElementById('hidden_weight_' + categoryId);
                        if (hiddenInput) {
                            hiddenInput.value = percentage.toFixed(2);
                        }
                        
                        // Update tampilan slider dan weight display saja (tanpa mengubah hidden input)
                        const valueDisplay = document.getElementById('importance_value_' + categoryId);
                        const weightDisplay = document.getElementById('weight_display_' + categoryId);
                        
                        const valueLabels = {
                            0: 'Tidak Penting (0)',
                            1: 'Sangat Rendah (1)', 
                            2: 'Rendah (2)',
                            3: 'Agak Rendah (3)',
                            4: 'Kurang (4)',
                            5: 'Sedang (5)',
                            6: 'Agak Penting (6)',
                            7: 'Penting (7)',
                            8: 'Sangat Penting (8)',
                            9: 'Krusial (9)',
                            10: 'Mutlak Perlu (10)'
                        };
                        
                        if (valueDisplay) {
                            valueDisplay.textContent = valueLabels[estimatedSliderValue] || `Level ${estimatedSliderValue}`;
                        }
                        
                        if (weightDisplay) {
                            if (percentage === 0) {
                                weightDisplay.textContent = '0% (Diabaikan)';
                                weightDisplay.style.color = '#9CA3AF'; // Gray-400
                            } else {
                                weightDisplay.textContent = percentage.toFixed(0) + '%';
                                weightDisplay.style.color = '#059669'; // Green-600
                            }
                        }
                    }
                });
            } else {
                // Mode normal: inisialisasi default
                allSliders.forEach(function(slider) {
                    const categoryId = slider.getAttribute('data-id');
                    const currentValue = parseInt(slider.value) || 3; // Sesuaikan dengan defaultImportance di PHP
                    
                    // Update tampilan dan perhitungan
                    updateSliderValue(categoryId, currentValue);
                });
            }
        });
        
        // Fungsi navigasi antar section
        function showSection1() {
            document.getElementById('section1').style.display = 'block';
            document.getElementById('section2').style.display = 'none';
            document.getElementById('section3').style.display = 'none';
        }
        
        function showSection2() {
            document.getElementById('section1').style.display = 'none';
            document.getElementById('section2').style.display = 'block';
            document.getElementById('section3').style.display = 'none';
        }
        
        function showSection3() {
            document.getElementById('section1').style.display = 'none';
            document.getElementById('section2').style.display = 'none';
            document.getElementById('section3').style.display = 'block';
        }
        
        // Inisialisasi tampilan berdasarkan apakah ada hasil pencarian
        document.addEventListener('DOMContentLoaded', function() {
            const hasSearchResults = "{{ request()->has('weight') ? 'true' : 'false' }}";
            if (hasSearchResults === 'true') {
                showSection3();
            } else {
                showSection1();
            }
            
            // Tambahkan event listener untuk form submit
            const smartForm = document.getElementById('smartForm');
            if (smartForm) {
                smartForm.addEventListener('submit', function(e) {
                    // Sebelum submit, pastikan semua hidden input terupdate dengan nilai slider terbaru
                    const sliders = document.querySelectorAll('input[type="range"]');
                    
                    // Kumpulkan semua nilai slider terlebih dahulu
                    let allValues = {};
                    sliders.forEach(function(slider) {
                        const categoryId = slider.getAttribute('data-id');
                        if (categoryId) {
                            allValues[categoryId] = parseInt(slider.value) || 0;
                        }
                    });
                    
                    // Hitung normalisasi manual untuk memastikan konsistensi
                    let totalRaw = 0;
                    Object.values(allValues).forEach(val => totalRaw += val * 10);
                    
                    // Update hidden inputs dengan nilai yang dinormalisasi
                    Object.keys(allValues).forEach(categoryId => {
                        const hiddenInput = document.getElementById('hidden_weight_' + categoryId);
                        if (hiddenInput) {
                            if (totalRaw > 0) {
                                const normalizedWeight = ((allValues[categoryId] * 10) / totalRaw) * 100;
                                hiddenInput.value = normalizedWeight.toFixed(2);
                            } else {
                                // Jika semua slider 0, bagi rata
                                const equalWeight = 100 / Object.keys(allValues).length;
                                hiddenInput.value = equalWeight.toFixed(2);
                            }
                        }
                    });
                });
            }
        });
    </script>
    
    <div class="bg-gray-200 dark:bg-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            
            <!-- @auth
            <div class="flex justify-end mb-4">
                <a href="{{ route('cafe.search-history') }}" class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg transition-colors flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Rekomendasi
                </a>
            </div>
            @endauth -->
            
            <!-- Search Form -->
            <div class="bg-gray-200 dark:bg-gray-200 rounded-2xl overflow-hidden mb-10">
                <form id="smartForm" action="{{ route('cafe.smart-search') }}" method="GET">
                    <!-- SECTION 1: Preference Weights -->
                    <div id="section1">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-950 border-b pb-3">Langkah 1: Tentukan Prioritas Anda</h2>
                        <p class="text-gray-600 dark:text-gray-950 mb-6">Geser slider untuk menunjukkan seberapa penting setiap kriteria. Atur ke 0 jika kriteria tidak penting. Bobot akan dihitung otomatis (total 100%).</p>
                        
                        @php
                            $categories = \App\Models\Category::all();
                            $categoryCount = $categories->count();
                            $defaultImportance = 3; // Default tingkat kepentingan (agak rendah, agar user bisa memilih)
                            $requestWeights = request('weight') ?? [];
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($categories as $index => $category)
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 rounded-lg">
                                <label for="importance_{{ $category->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $category->name }}</label>
                                <div class="flex flex-col">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-gray-500">Tidak Penting</span>
                                        <span class="text-xs text-gray-500">Sangat Penting</span>
                                    </div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">0</span>
                                        <span class="text-xs text-gray-500">10</span>
                                    </div>
                                    @php
                                        // Jika ada request weight (dari hasil pencarian), gunakan itu untuk slider
                                        // Tapi kita perlu konversi dari persentase kembali ke nilai slider (0-10)
                                        if (isset($requestWeights[$category->id])) {
                                            // Request weight adalah persentase, konversi ke slider value
                                            $percentage = floatval($requestWeights[$category->id]);
                                            // Estimasi slider value dari persentase
                                            // Ini tidak sempurna tapi cukup untuk restore UI
                                            if ($percentage == 0) {
                                                $currentSliderValue = 0;
                                            } elseif ($percentage >= 80) {
                                                $currentSliderValue = 10;
                                            } elseif ($percentage >= 60) {
                                                $currentSliderValue = 8;
                                            } elseif ($percentage >= 40) {
                                                $currentSliderValue = 6;
                                            } elseif ($percentage >= 20) {
                                                $currentSliderValue = 4;
                                            } else {
                                                $currentSliderValue = 2;
                                            }
                                            $defaultHiddenValue = $percentage;
                                        } else {
                                            $currentSliderValue = $defaultImportance;
                                            // Hitung default bobot berdasarkan proporsi (3 dari 10 untuk setiap kategori, total akan dinormalisasi ke 100%)
                                            $defaultHiddenValue = (100 / $categoryCount); // Default bobot merata
                                        }
                                    @endphp
                                    <input 
                                        type="range" 
                                        min="0" 
                                        max="10" 
                                        value="{{ $currentSliderValue }}"
                                        id="importance_{{ $category->id }}" 
                                        class="importance-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-blue-600"
                                        data-id="{{ $category->id }}"
                                        data-request-weight="{{ $requestWeights[$category->id] ?? '' }}"
                                        oninput="updateSliderValue('{{ $category->id }}', this.value)"
                                    >
                                    <div class="flex justify-between mt-4">
                                        <span id="importance_value_{{ $category->id }}" class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            @if(isset($requestWeights[$category->id]))
                                                @php
                                                    $valueLabels = [
                                                        0 => 'Tidak Penting (0)',
                                                        1 => 'Sangat Rendah (1)', 
                                                        2 => 'Rendah (2)',
                                                        3 => 'Agak Rendah (3)',
                                                        4 => 'Kurang (4)',
                                                        5 => 'Sedang (5)',
                                                        6 => 'Agak Penting (6)',
                                                        7 => 'Penting (7)',
                                                        8 => 'Sangat Penting (8)',
                                                        9 => 'Krusial (9)',
                                                        10 => 'Mutlak Perlu (10)'
                                                    ];
                                                @endphp
                                                {{ $valueLabels[$currentSliderValue] ?? "Level $currentSliderValue" }}
                                            @else
                                                Agak Rendah (3)
                                            @endif
                                        </span>
                                        <span id="weight_display_{{ $category->id }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($defaultHiddenValue, 0) }}%</span>
                                    </div>
                                    <input type="hidden" name="weight[{{ $category->id }}]" id="hidden_weight_{{ $category->id }}" value="{{ $defaultHiddenValue }}">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-12 pt-6  flex justify-end">
                            <button type="button" id="nextButton1" onclick="showSection2()" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-8 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium shadow-md flex items-center">
                                <span>Lanjut ke Kriteria</span>
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg> -->
                            </button>
                        </div>
                    </div>
                    
                    <!-- SECTION 2: Criteria Selection & Location -->
                    <div id="section2" style="display: none;">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-950 border-b pb-3">Langkah 2: Pilih Kriteria Spesifik (Opsional)</h2>
                        <p class="text-gray-600 dark:text-gray-950 mb-6">Pilih kriteria spesifik jika ada preferensi khusus. Café yang cocok akan mendapat nilai lebih.</p>
                        
                        <!-- Tambahkan Filter Area -->
                        <div class="bg-gray-200 dark:bg-gray-200 rounded-xl transition-shadow duration-300 mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-950 mb-3">Filter berdasarkan Area</label>
                            <div class="flex flex-wrap">
                                <input type="checkbox" id="area_indoor" name="area[]" value="Indoor" class="area-checkbox" {{ is_array(request('area')) && in_array('Indoor', request('area')) ? 'checked' : '' }}>
                                <label for="area_indoor" class="area-checkbox-label">Indoor</label>
                                
                                <input type="checkbox" id="area_semi_outdoor" name="area[]" value="Semi Outdoor" class="area-checkbox" {{ is_array(request('area')) && in_array('Semi Outdoor', request('area')) ? 'checked' : '' }}>
                                <label for="area_semi_outdoor" class="area-checkbox-label">Semi Outdoor</label>
                                
                                <input type="checkbox" id="area_outdoor" name="area[]" value="Outdoor" class="area-checkbox" {{ is_array(request('area')) && in_array('Outdoor', request('area')) ? 'checked' : '' }}>
                                <label for="area_outdoor" class="area-checkbox-label">Outdoor</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $categoriesWithSubs = \App\Models\Category::all();
                                $requestCriteria = request('criteria') ?? [];
                                $requestLokasi = request('lokasi');
                            @endphp
                            
                            @foreach($categoriesWithSubs as $category)
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 rounded-lg">
                                <label for="criteria_{{ $category->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $category->name }}</label>
                                <select id="criteria_{{ $category->id }}" name="criteria[{{ $category->id }}]" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white">
                                    <!-- <option value="">Semua {{ strtolower($category->name) }}</option> -->
                                    
                                    @php
                                        $subcategories = \App\Models\Subcategory::where('category_id', $category->id)->get();
                                        // Cari subcategory dengan value 5 sebagai default
                                        $defaultSubcategory = $subcategories->where('value', 5)->first();
                                        $defaultSubcategoryId = $defaultSubcategory ? $defaultSubcategory->id : null;
                                        
                                        // Jika ada request criteria, gunakan itu. Jika tidak, gunakan default subcategory dengan value 5
                                        $selectedSubcategoryId = isset($requestCriteria[$category->id]) ? 
                                                                $requestCriteria[$category->id] : 
                                                                $defaultSubcategoryId;
                                    @endphp
                                    
                                    @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ $selectedSubcategoryId == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                    
                        <!-- Location (Optional)
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Lokasi (Opsional)</h3>
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter berdasarkan area di Jember</label>
                                <select id="lokasi" name="lokasi" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white">
                                    <option value="">Semua area</option>
                                    <option value="sumbersari" {{ $requestLokasi == 'sumbersari' ? 'selected' : '' }}>Sumbersari</option>
                                    <option value="patrang" {{ $requestLokasi == 'patrang' ? 'selected' : '' }}>Patrang</option>
                                    <option value="kaliwates" {{ $requestLokasi == 'kaliwates' ? 'selected' : '' }}>Kaliwates</option>
                                    <option value="tegal-gede" {{ $requestLokasi == 'tegal-gede' ? 'selected' : '' }}>Tegal Gede</option>
                                    <option value="kampus" {{ $requestLokasi == 'kampus' ? 'selected' : '' }}>Sekitar Kampus</option>
                                </select>
                            </div>
                        </div> -->
                        
                        <div class="mt-12 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            <button type="submit" id="submitButton" class="w-full md:w-auto bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-8 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium shadow-md">
                                <span class="flex items-center justify-center">
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg> -->
                                    Cari Café Rekomendasi
                                </span>
                            </button>
                            <button type="button" id="backButton1" onclick="showSection1()" class="w-full md:w-auto bg-gradient-to-r from-gray-200 to-gray-300 text-gray-900 dark:text-gray-950 py-3 px-8 rounded-lg hover:from-gray-300 hover:to-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 font-medium flex items-center justify-center">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg> -->
                                <span>Kembali ke Bobot</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- SECTION 3: Results Container -->
            <div id="section3" style="display: none;">
                @if(request()->has('weight'))
                <div id="results" class="mb-10">
                    <div class="flex justify-between items-center mb-6 border-b pb-3">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-950">Hasil Rekomendasi SMART</h2>
                         <!-- <button type="button" id="backToSearchButton" onclick="showSection2()" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 font-medium text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                            Kembali ke Pencarian
                        </button> -->
                    </div>
                    
                    @php
                        // Implementasi algoritma SMART
                        $cafes = \App\Models\Cafe::with(['ratings.subcategory', 'ratings.category'])->get();
                        $weights = request('weight') ?? [];
                        $criterias = request('criteria') ?? [];
                        $lokasi = request('lokasi');
                        $selectedAreas = request('area') ?? [];
                        
                        // Frontend sudah mengirim bobot yang dinormalisasi (total ~100%)
                        // Jadi kita gunakan langsung tanpa normalisasi ulang
                        $totalWeight = array_sum($weights);
                        
                        // Jika tidak ada weight atau total 0, buat bobot merata
                        if ($totalWeight == 0 || empty($weights)) {
                            $categories = \App\Models\Category::all();
                            foreach ($categories as $category) {
                                $weights[$category->id] = 100 / $categories->count();
                            }
                            $totalWeight = 100;
                        }
                        
                        // Debug: tampilkan bobot yang diterima dari frontend
                        $rawWeights = request('weight') ?? [];
                        $debugRawWeights = [];
                        foreach ($rawWeights as $catId => $weight) {
                            $category = \App\Models\Category::find($catId);
                            if ($category) {
                                $debugRawWeights[] = $category->name . ': ' . number_format($weight, 2) . '%';
                            }
                        }
                        
                        // Debug: tampilkan bobot yang diterima
                        $debugWeights = [];
                        foreach ($weights as $catId => $weight) {
                            $category = \App\Models\Category::find($catId);
                            if ($category) {
                                $debugWeights[] = $category->name . ': ' . number_format($weight, 1) . '%';
                            }
                        }
                        
                        $selectedCriteria = [];
                        if (!empty($criterias)) {
                            foreach ($criterias as $categoryId => $subcategoryId) {
                                if ($subcategoryId) {
                                    $category = \App\Models\Category::find($categoryId);
                                    $subcategory = \App\Models\Subcategory::find($subcategoryId);
                                    if ($category && $subcategory) {
                                        $selectedCriteria[] = $category->name . ': ' . $subcategory->name;
                                    }
                                }
                            }
                        }

                        // Filter berdasarkan lokasi jika ada
                        if ($lokasi) {
                            $cafes = $cafes->filter(function($cafe) use ($lokasi) {
                                return stripos($cafe->lokasi, $lokasi) !== false;
                            });
                        }
                        
                        // Filter berdasarkan area yang dipilih
                        if (!empty($selectedAreas)) {
                            $cafes = $cafes->filter(function($cafe) use ($selectedAreas) {
                                // Jika cafe tidak punya area, lewati
                                if (!$cafe->area) return false;
                                
                                // Cek apakah cafe memiliki setidaknya satu area yang dipilih
                                foreach ($selectedAreas as $selectedArea) {
                                    if (stripos($cafe->area, $selectedArea) !== false) {
                                        return true;
                                    }
                                }
                                
                                return false;
                            });
                        }
                        
                        // Hitung skor SMART untuk setiap cafe
                        $cafeScores = [];
                        $debugOutput = "";
                        
                        // Metode alternatif yang lebih langsung - ambil data dari database
                        foreach ($cafes as $cafe) {
                            $score = 0;
                            $debugInfo = [];
                            
                            // Query langsung ke database untuk mendapatkan data yang diperlukan
                            $ratings = \Illuminate\Support\Facades\DB::table('cafe_ratings')
                                ->join('subcategories', 'cafe_ratings.subcategory_id', '=', 'subcategories.id')
                                ->join('categories', 'cafe_ratings.category_id', '=', 'categories.id')
                                ->where('cafe_ratings.cafe_id', $cafe->id)
                                ->select(
                                    'cafe_ratings.category_id',
                                    'categories.name as category_name',
                                    'cafe_ratings.subcategory_id',
                                    'subcategories.name as subcategory_name',
                                    'subcategories.value'
                                )
                                ->get();
                            
                            $debugOutput .= "<div style='margin-bottom:5px;padding:5px;background:#f8f8f8;border:1px solid #ddd;'>";
                            $debugOutput .= "<strong>Cafe: " . $cafe->nama . "</strong><br>";
                            
                            foreach ($ratings as $rating) {
                                // Jika ada bobot untuk kategori ini
                                if (isset($weights[$rating->category_id])) {
                                    $weight = floatval($weights[$rating->category_id]);
                                    // Frontend sudah mengirim persentase yang dinormalisasi (total = 100%)
                                    // Jadi kita gunakan langsung, konversi dari persentase ke desimal
                                    $normalizedWeight = $weight / 100;
                                    
                                    // Hanya proses jika bobot > 0
                                    if ($weight > 0) {
                                        // Bonus nilai jika kriteria dipilih
                                        if (isset($criterias[$rating->category_id]) && 
                                            $criterias[$rating->category_id] == $rating->subcategory_id) {
                                            $value = 5; // Nilai maksimal untuk yang dipilih
                                        } else {
                                            // Hitung jarak dengan nilai yang dipilih
                                            $selectedValue = 0;
                                            if (isset($criterias[$rating->category_id])) {
                                                $selectedSubcategory = \App\Models\Subcategory::find($criterias[$rating->category_id]);
                                                if ($selectedSubcategory) {
                                                    $selectedValue = $selectedSubcategory->value;
                                                }
                                            }
                                            
                                            // Hitung nilai berdasarkan jarak dengan nilai yang dipilih
                                            $originalValue = intval($rating->value);
                                            $distance = abs($originalValue - $selectedValue);
                                            
                                            // Nilai akan lebih tinggi jika lebih dekat dengan nilai yang dipilih
                                            // Nilai maksimal 4 untuk yang paling dekat, minimal 1
                                            $value = max(1, 5 - $distance);
                                        }
                                        
                                        // Hitung utilitas
                                        $utilityValue = ($value - 1) / 4; // Normalisasi ke rentang 0-1 (jika nilai antara 1-5)
                                        $ratingScore = $normalizedWeight * $utilityValue;
                                        $score += $ratingScore;
                                        
                                        $debugOutput .= "&nbsp; " . $rating->category_name . ": " .
                                            "Weight=" . number_format($weight, 1) . "%, " .
                                            "Value=" . $value . ", " .
                                            "Score=" . number_format($ratingScore, 2) . "<br>";
                                        
                                        // Simpan untuk debug
                                        $debugInfo[$rating->category_name] = [
                                            'bobot' => $weight,
                                            'bobot_normal' => $normalizedWeight,
                                            'nilai' => $value,
                                            'nilai_asli' => $rating->value,
                                            'subcategory' => $rating->subcategory_name,
                                            'skor_kriteria' => $ratingScore
                                        ];
                                    } else {
                                        // Kategori dengan bobot 0, diabaikan dalam perhitungan
                                        $debugOutput .= "&nbsp; " . $rating->category_name . ": " .
                                            "Weight=0% (Diabaikan)<br>";
                                        
                                        // Simpan untuk debug dengan skor 0
                                        $debugInfo[$rating->category_name] = [
                                            'bobot' => 0,
                                            'bobot_normal' => 0,
                                            'nilai' => 0,
                                            'nilai_asli' => $rating->value,
                                            'subcategory' => $rating->subcategory_name,
                                            'skor_kriteria' => 0
                                        ];
                                    }
                                }
                            }
                            
                            $debugOutput .= "Total Score: " . number_format($score, 2) . "</div>";
                            
                            $cafeScores[$cafe->id] = [
                                'score' => $score,
                                'cafe' => $cafe,
                                'debug' => $debugInfo
                            ];
                        }
                        
                        // Urutkan cafe berdasarkan skor tertinggi, dengan logika khusus untuk kriteria harga mahal
                        uasort($cafeScores, function($a, $b) use ($criterias) {
    // Primary sorting: berdasarkan skor SMART (tertinggi dulu)
    $scoreComparison = $b['score'] <=> $a['score'];
    
    // Jika skor sama, gunakan secondary sorting berdasarkan harga
    if ($scoreComparison === 0) {
        $hargaCategoryId = null;
        $isMahalSelected = false;
        $isMurahSelected = false;
        
        // Cari kategori harga dan cek subkategori apa yang dipilih
        foreach ($criterias as $categoryId => $subcategoryId) {
            if ($subcategoryId) {
                $category = \App\Models\Category::find($categoryId);
                if ($category && strtolower($category->name) === 'harga') {
                    $hargaCategoryId = $categoryId;
                    $subcategory = \App\Models\Subcategory::find($subcategoryId);
                    if ($subcategory) {
                        $nameLower = strtolower($subcategory->name);
                        if (stripos($nameLower, 'mahal') !== false) {
                            $isMahalSelected = true;
                        } elseif (stripos($nameLower, 'murah') !== false) {
                            $isMurahSelected = true;
                        }
                    }
                    break;
                }
            }
        }

        // Jika user memilih "Mahal"
        if ($isMahalSelected) {
            $hargaTermahalA = $a['cafe']->harga_termahal ?? 0;
            $hargaTermahalB = $b['cafe']->harga_termahal ?? 0;
            $hargaTermahalComparison = $hargaTermahalB <=> $hargaTermahalA;

            if ($hargaTermahalComparison === 0) {
                $hargaTermurahA = $a['cafe']->harga_termurah ?? 0;
                $hargaTermurahB = $b['cafe']->harga_termurah ?? 0;
                return $hargaTermurahB <=> $hargaTermurahA;
            }

            return $hargaTermahalComparison;

        // Jika user memilih "Murah"
        } elseif ($isMurahSelected) {
            $rataA = (($a['cafe']->harga_termurah ?? 0) + ($a['cafe']->harga_termahal ?? 0)) / 2;
            $rataB = (($b['cafe']->harga_termurah ?? 0) + ($b['cafe']->harga_termahal ?? 0)) / 2;
            return $rataA <=> $rataB; // Ascending (murah ke mahal)

        // Jika tidak memilih mahal/murah secara spesifik
        } else {
            $hargaTermurahA = $a['cafe']->harga_termurah ?? PHP_INT_MAX;
            $hargaTermurahB = $b['cafe']->harga_termurah ?? PHP_INT_MAX;
            $hargaTermurahComparison = $hargaTermurahA <=> $hargaTermurahB;

            if ($hargaTermurahComparison === 0) {
                $hargaTermahalA = $a['cafe']->harga_termahal ?? PHP_INT_MAX;
                $hargaTermahalB = $b['cafe']->harga_termahal ?? PHP_INT_MAX;
                return $hargaTermahalA <=> $hargaTermahalB;
            }

            return $hargaTermurahComparison;
        }
    }

    return $scoreComparison;
});

                        
                        // Membuat array cafe yang sudah diurutkan berdasarkan skor
                        $rankedCafes = collect();
                        foreach ($cafeScores as $cafeScore) {
                            $rankedCafes->push($cafeScore['cafe']);
                        }
                        
                        // Ambil hanya 10 cafe teratas
                        $rankedCafes = $rankedCafes->take(10);
                        
                        // Array untuk akses skor pada tampilan
                        $cafeScoresById = [];
                        foreach ($cafeScores as $cafeId => $scoreData) {
                            $cafeScoresById[$cafeId] = $scoreData;
                        }
                    @endphp
                    
                    @if(count($selectedCriteria) > 0 || $lokasi || !empty($selectedAreas))
                    <div class="mb-6 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Kriteria Pencarian:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedCriteria as $criteria)
                                <span class="text-sm font-medium rounded-full text-gray-700 dark:text-gray-300">{{ $criteria }}</span>
                            @endforeach
                            
                            <!-- @if($lokasi)
                                <span class="text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Lokasi: {{ $lokasi }}</span>
                            @endif
                            
                            @foreach($selectedAreas as $area)
                                <span class="text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Area: {{ $area }}</span>
                            @endforeach -->
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if($rankedCafes->isEmpty())
                            <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-600 dark:text-gray-400">Tidak ada café yang sesuai dengan filter lokasi Anda.</p>
                                <p class="mt-2 text-gray-500 dark:text-gray-500">Coba ubah filter lokasi atau gunakan pencarian tanpa filter.</p>
                            </div>
                        @else
                            @foreach($rankedCafes as $index => $cafe)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 relative flex flex-col rounded-lg">
                                    <!-- Tambahkan badge peringkat -->
                                    <div class="absolute top-0 left-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-2 px-4 rounded-br-lg shadow-md z-10">
                                        Peringkat {{ $index + 1 }}
                                    </div>
                                    
                                    <!-- Image container dengan tinggi tetap -->
                                    <div style="height: 200px; min-height: 200px; max-height: 200px; overflow: hidden; position: relative;" class="w-full flex-shrink-0 rounded-lg">
                                        @if($cafe->gambar)
                                            <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" class="bg-gradient-to-r from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content container -->
                                    <div class="p-4 flex-grow">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $cafe->nama }}</h3>
                                        <p class="text-gray-600 dark:text-gray-300 mb-2">
                                            <span class="inline-block">{{ $cafe->area }}</span>
                                        </p>
                                        
                                        <!-- Tampilkan range harga -->
                                        @if($cafe->harga_termurah || $cafe->harga_termahal)
                                        <div class="mb-3 text-sm">
                                            <span class="font-medium text-gray-600 dark:text-gray-300">
                                                Range Harga: 
                                                @if($cafe->harga_termurah && $cafe->harga_termahal)
                                                    Rp {{ number_format($cafe->harga_termurah, 0, ',', '.') }} - Rp {{ number_format($cafe->harga_termahal, 0, ',', '.') }}
                                                @elseif($cafe->harga_termurah)
                                                    Mulai dari Rp {{ number_format($cafe->harga_termurah, 0, ',', '.') }}
                                                @elseif($cafe->harga_termahal)
                                                    Hingga Rp {{ number_format($cafe->harga_termahal, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </div>
                                        @endif
                                        
                                        <div class="">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <ul class="space-y-2">
                                                    @foreach($cafe->ratings as $rating)
                                                        <li class="flex justify-between">
                                                            <span class="font-medium">{{ $rating->category->name }}:</span> 
                                                            <span class="text-yellow-500 ml-1">
                                                                @for ($i = 0; $i < $rating->subcategory->value; $i++)
                                                                    ★
                                                                @endfor
                                                                @for ($i = $rating->subcategory->value; $i < 5; $i++)
                                                                    ☆
                                                                @endfor
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Tambahkan detail perhitungan skor -->
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <details class="group">
                                                    <summary class="font-medium mb-1 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 flex items-center">
                                                        <span>Detail Perhitungan SMART</span>
                                                        <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </summary>
                                                    <div class="mt-2 text-xs bg-gray-50 dark:bg-gray-800 p-2 rounded-lg">
                                                        @if(isset($cafeScoresById[$cafe->id]['debug']) && !empty($cafeScoresById[$cafe->id]['debug']))
                                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                                    <tr class="border-b dark:border-gray-700">
                                                                        <th class="text-left py-1 px-2">Kriteria</th>
                                                                        <th class="text-right py-1 px-2">Bobot</th>
                                                                        <th class="text-left py-1 px-2">Subkategori</th>
                                                                        <th class="text-right py-1 px-2">Nilai</th>
                                                                        <th class="text-right py-1 px-2">Skor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                    @php $totalScore = 0; @endphp
                                                                    @foreach($cafeScoresById[$cafe->id]['debug'] as $category => $info)
                                                                        <tr>
                                                                            <td class="text-left py-1 px-2">{{ $category }}</td>
                                                                            <td class="text-right py-1 px-2">{{ number_format($info['bobot'], 0) }}%</td>
                                                                            <td class="text-left py-1 px-2">{{ $info['subcategory'] ?? 'N/A' }}</td>
                                                                            <td class="text-right py-1 px-2">{{ number_format($info['nilai'], 1) }}</td>
                                                                            <td class="text-right py-1 px-2">{{ number_format($info['skor_kriteria'], 2) }}</td>
                                                                        </tr>
                                                                        @php $totalScore += $info['skor_kriteria']; @endphp
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                                                                    <tr>
                                                                        <td class="py-1 px-2">Total</td>
                                                                        <td class="text-right py-1 px-2">100%</td>
                                                                        <td class="text-left py-1 px-2"></td>
                                                                        <td class="text-right py-1 px-2"></td>
                                                                        <td class="text-right py-1 px-2">{{ number_format($totalScore, 2) }}</td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        @else
                                                            <p>Tidak ada detail perhitungan tersedia.</p>
                                                        @endif
                                                    </div>
                                                </details>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex justify-between items-center">
                                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                Skor: {{ number_format($cafeScoresById[$cafe->id]['score'], 2) }}
                                            </div>
                                            <a href="{{ route('cafes.show', $cafe->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm font-medium flex items-center">
                                                Lihat Detail
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @else
                <!-- Placeholder jika belum ada pencarian -->
                <div id="resultsPlaceholder" class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-blue-400 dark:text-blue-500 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-medium text-gray-700 dark:text-gray-300">Mulai Pencarian Café</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-400 max-w-md mx-auto">Atur prioritas Anda dan pilih kriteria yang diinginkan, lalu klik "Cari Café Rekomendasi" untuk menemukan café yang sesuai dengan preferensi Anda.</p>
                    <button onclick="showSection1()" class="mt-6 px-6 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-300 shadow-md flex items-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        Mulai Pencarian
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection 