@extends('layouts.app')

@section('title', ' - Pencarian Café')

@php
    // Tentukan langkah saat ini berdasarkan parameter URL
    if (request()->has('weight')) {
        $currentStep = 3; // Jika ada hasil pencarian (parameter weight), berarti di langkah hasil
    } elseif (old('criteria') || request()->has('criteria')) {
        $currentStep = 2; // Jika ada kriteria yang dipilih, berarti di langkah kriteria
    } else {
        $currentStep = 1; // Default langkah awal
    }
@endphp

@section('stepper')
    <x-stepper :currentStep="$currentStep" />
@endsection

@section('content')
    <script>
    //<![CDATA[
        // Cek apakah halaman memiliki hasil pencarian (parameter weight di URL)
        const hasSearchResults = {{ request()->has('weight') ? 'true' : 'false' }};
        
        // Global data untuk menyimpan nilai penting setiap kriteria
        const importanceValues = {};
    //]]>
    </script>
    
    <script>
        // Label untuk tingkat kepentingan
        const importanceLabels = {
            1: "Sangat tidak penting (1)", 
            2: "Tidak penting (2)", 
            3: "Kurang penting (3)", 
            4: "Agak penting (4)",
            5: "Sedang (5)", 
            6: "Cukup penting (6)", 
            7: "Penting (7)", 
            8: "Sangat penting (8)", 
            9: "Sangat penting sekali (9)", 
            10: "Paling penting (10)"
        };
        
        // Fungsi untuk update nilai slider yang dipanggil langsung dari oninput HTML
        function updateSliderValue(id, value) {
            // Update nilai penting di object global
            importanceValues[id] = parseInt(value);
            
            // Update tampilan label tingkat kepentingan
            const importanceDisplay = document.getElementById(`importance_value_${id}`);
            if (importanceDisplay) {
                importanceDisplay.textContent = importanceLabels[value];
            }
            
            // Hitung ulang bobot dan perbarui tampilan
            calculateAndUpdateWeights();
        }
        
        // Fungsi untuk menghitung bobot berdasarkan tingkat kepentingan
        function calculateAndUpdateWeights() {
            let totalImportance = 0;
            Object.values(importanceValues).forEach(value => {
                totalImportance += value;
            });
            
            // Hitung bobot berdasarkan proporsi kepentingan
            const weights = {};
            let totalWeight = 0;
            
            if (totalImportance > 0) {
                // Hitung berdasarkan proporsi
                Object.keys(importanceValues).forEach(id => {
                    weights[id] = Math.floor((importanceValues[id] / totalImportance) * 100);
                    totalWeight += weights[id];
                });
                
                // Distribusikan sisa persen
                if (totalWeight < 100) {
                    // Urutkan ID berdasarkan tingkat kepentingan (tertinggi dulu)
                    const sortedIds = Object.keys(importanceValues).sort((a, b) => 
                        importanceValues[b] - importanceValues[a]
                    );
                    
                    // Distribusikan sisa ke nilai tertinggi
                    let remainder = 100 - totalWeight;
                    for (let i = 0; i < remainder; i++) {
                        weights[sortedIds[i % sortedIds.length]]++;
                    }
                }
            } else {
                // Distribusi merata jika semua nilai penting adalah 0
                const equalWeight = Math.floor(100 / Object.keys(importanceValues).length);
                let remainder = 100 - (equalWeight * Object.keys(importanceValues).length);
                
                Object.keys(importanceValues).forEach((id, index) => {
                    weights[id] = equalWeight + (index < remainder ? 1 : 0);
                });
            }
            
            // Update tampilan bobot dan input tersembunyi
            Object.keys(weights).forEach(id => {
                const weightDisplay = document.getElementById(`weight_display_${id}`);
                const hiddenInput = document.getElementById(`hidden_weight_${id}`);
                
                if (weightDisplay) {
                    weightDisplay.textContent = `${weights[id]}%`;
                    
                    // Terapkan kode warna berdasarkan nilai bobot
                    if (weights[id] >= 30) {
                        weightDisplay.className = 'text-sm font-medium text-green-600 dark:text-green-400';
                    } else if (weights[id] >= 15) {
                        weightDisplay.className = 'text-sm font-medium text-blue-600 dark:text-blue-400';
                    } else {
                        weightDisplay.className = 'text-sm font-medium text-gray-700 dark:text-gray-300';
                    }
                }
                
                if (hiddenInput) {
                    hiddenInput.value = weights[id];
                }
            });
            
            // Update indikator total bobot
            const totalWeightIndicator = document.getElementById('total_weight_indicator');
            if (totalWeightIndicator) {
                const calculatedTotal = Object.values(weights).reduce((sum, w) => sum + w, 0);
                totalWeightIndicator.textContent = `Total: ${calculatedTotal}%`;
                
                if (calculatedTotal === 100) {
                    totalWeightIndicator.classList.remove('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
                    totalWeightIndicator.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                } else {
                    totalWeightIndicator.classList.remove('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                    totalWeightIndicator.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
                }
            }
            
            return Object.values(weights).reduce((sum, w) => sum + w, 0) === 100;
        }
        
        // Fungsi untuk cek apakah halaman ini hasil dari submit form
        function checkIfSearchResults() {
            return hasSearchResults;
        }
        
        // Update fungsi showSection untuk memanggil updateStepProgress
        function showSection1() {
            document.getElementById('section1').style.display = 'block';
            document.getElementById('section2').style.display = 'none';
            document.getElementById('section3').style.display = 'none';
            updateStepperVisually(1);
            window.scrollTo(0, 0);
        }

        function showSection2() {
            document.getElementById('section1').style.display = 'none';
            document.getElementById('section2').style.display = 'block';
            document.getElementById('section3').style.display = 'none';
            updateStepperVisually(2);
            window.scrollTo(0, 0);
        }

        function showSection3() {
            document.getElementById('section1').style.display = 'none';
            document.getElementById('section2').style.display = 'none';
            document.getElementById('section3').style.display = 'block';
            updateStepperVisually(3);
            window.scrollTo(0, 0);
        }
        
        // Fungsi untuk memperbarui tampilan stepper
        function updateStepperVisually(step) {
            // Ambil semua step-item dari stepper
            const stepItems = document.querySelectorAll('.step-item');
            
            stepItems.forEach((item, index) => {
                const stepNum = index + 1;
                const circleDiv = item.querySelector('.w-10');
                const textSpan = item.querySelector('.text-sm');
                
                if (stepNum <= step) {
                    // Langkah aktif atau sudah dilalui
                    circleDiv.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                    circleDiv.classList.add('bg-blue-600', 'text-white');
                    
                    textSpan.classList.remove('text-gray-500', 'dark:text-gray-400');
                    textSpan.classList.add('text-blue-600', 'dark:text-blue-400');
                } else {
                    // Langkah yang belum aktif
                    circleDiv.classList.remove('bg-blue-600', 'text-white');
                    circleDiv.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                    
                    textSpan.classList.remove('text-blue-600', 'dark:text-blue-400');
                    textSpan.classList.add('text-gray-500', 'dark:text-gray-400');
                }
                
                // Update status aktif di class
                if (stepNum <= step) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }
    </script>
    
    <script>
    //<![CDATA[
        // Initialize stepper
        document.addEventListener('DOMContentLoaded', function() {
            try {
                console.log('DOM Content Loaded.');
                
                // Inisialisasi nilai penting dan bobot dari URL jika ada
                const sliders = document.querySelectorAll('.importance-slider');
                sliders.forEach(slider => {
                    const id = slider.dataset.id;
                    const requestWeight = slider.dataset.requestWeight;
                    
                    // Jika ada bobot dari request
                    if (requestWeight) {
                        // Cari nilai importance yang sesuai dengan bobot
                        for (let i = 1; i <= 10; i++) {
                            importanceValues[id] = i;
                            calculateAndUpdateWeights();
                            const weight = document.getElementById(`hidden_weight_${id}`).value;
                            if (parseInt(weight) === parseInt(requestWeight)) {
                                slider.value = i;
                                updateSliderValue(id, i);
                                break;
                            }
                        }
                    } else {
                        // Default nilai awal
                        const defaultValue = slider.value;
                        importanceValues[id] = parseInt(defaultValue);
                    }
                });
                
                // Update semua bobot
                calculateAndUpdateWeights();
                
                // Inisialisasi stepper berdasarkan variabel PHP
                const currentStep = {{ $currentStep }};
                
                // Tampilkan section sesuai langkah
                if (checkIfSearchResults()) {
                    showSection3();
                } else if (currentStep === 2) {
                    showSection2();
                } else {
                    showSection1();
                }
                
                // Form submission validation - ensure weights sum to 100%
                const smartForm = document.getElementById('smartForm');
                if (smartForm) {
                    smartForm.addEventListener('submit', function(event) {
                        if (!calculateAndUpdateWeights()) {
                            event.preventDefault();
                            alert('Total bobot harus 100%. Silakan sesuaikan tingkat kepentingan.');
                            showSection1();
                        }
                    });
                }
                
            } catch (error) {
                console.error('Terjadi error saat inisialisasi halaman:', error);
            }
        });
    //]]>
    </script>
    <div class="bg-white dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-4xl font-bold mb-4 text-gray-800 dark:text-white text-center">Pencarian Café</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 text-center mb-10">Temukan café yang sesuai dengan preferensi dan kebutuhan Anda</p>
            
            <!-- Search Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden p-6 mb-10">
                <form id="smartForm" action="{{ route('search-cafe') }}" method="GET">
                    <!-- SECTION 1: Preference Weights -->
                    <div id="section1">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white border-b pb-3">Langkah 1: Tentukan Prioritas Anda</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Geser slider untuk menunjukkan seberapa penting setiap kriteria. Bobot akan dihitung otomatis (total 100%).</p>
                        
                        @php
                            $categories = \App\Models\Category::all();
                            $categoryCount = $categories->count();
                            $defaultImportance = 5; // Default tingkat kepentingan
                            $requestWeights = request('weight') ?? []; // Ambil bobot dari request jika ada
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($categories as $index => $category)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-300">
                                <label for="importance_{{ $category->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $category->name }}</label>
                                <div class="flex flex-col">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-gray-500">Kurang</span>
                                        <span class="text-xs text-gray-500">Penting</span>
                                    </div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">1</span>
                                        <span class="text-xs text-gray-500">10</span>
                                    </div>
                                    <input 
                                        type="range" 
                                        min="1" 
                                        max="10" 
                                        value="{{ $defaultImportance }}"
                                        id="importance_{{ $category->id }}" 
                                        class="importance-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-blue-600"
                                        data-id="{{ $category->id }}"
                                        data-request-weight="{{ $requestWeights[$category->id] ?? '' }}"
                                        oninput="updateSliderValue('{{ $category->id }}', this.value)"
                                    >
                                    <div class="flex justify-between mt-2">
                                        <span id="importance_value_{{ $category->id }}" class="text-sm font-medium text-blue-600 dark:text-blue-400">Sedang (5)</span>
                                        <span id="weight_display_{{ $category->id }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">0%</span>
                                    </div>
                                    <input type="hidden" name="weight[{{ $category->id }}]" id="hidden_weight_{{ $category->id }}" value="0">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" id="nextButton1" onclick="showSection2()" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium flex items-center">
                                Lanjut ke Kriteria <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- SECTION 2: Criteria Selection & Location -->
                    <div id="section2" style="display: none;">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white border-b pb-3">Langkah 2: Pilih Kriteria Spesifik (Opsional)</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">Pilih kriteria spesifik jika ada preferensi khusus. Café yang cocok akan mendapat nilai lebih.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $categoriesWithSubs = \App\Models\Category::all();
                                $requestCriteria = request('criteria') ?? [];
                                $requestLokasi = request('lokasi');
                            @endphp
                            
                            @foreach($categoriesWithSubs as $category)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-300">
                                <label for="criteria_{{ $category->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $category->name }}</label>
                                <select id="criteria_{{ $category->id }}" name="criteria[{{ $category->id }}]" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 dark:text-white">
                                    <option value="">Semua {{ strtolower($category->name) }}</option>
                                    
                                    @php
                                        $subcategories = \App\Models\Subcategory::where('category_id', $category->id)->get();
                                    @endphp
                                    
                                    @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ isset($requestCriteria[$category->id]) && $requestCriteria[$category->id] == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 flex justify-between items-center">
                             <button type="button" id="backButton1" onclick="showSection1()" class="bg-gray-200 text-gray-700 py-2 px-6 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                                Kembali ke Bobot
                            </button>
                            <button type="submit" id="submitButton" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-8 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 font-medium shadow-md">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                    Cari Café Rekomendasi
                                </span>
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
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Hasil Rekomendasi SMART</h2>
                         <button type="button" id="backToSearchButton" onclick="showSection2()" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 font-medium text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                            Kembali ke Pencarian
                        </button>
                    </div>
                    
                    @php
                        // Implementasi algoritma SMART
                        $cafes = \App\Models\Cafe::with(['ratings.subcategory', 'ratings.category'])->get();
                        $weights = request('weight') ?? [];
                        $criterias = request('criteria') ?? [];
                        $lokasi = request('lokasi');
                        
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
                        
                        // Hitung total semua bobot untuk normalisasi (atau default 100 jika kosong)
                        $totalWeight = array_sum($weights);
                        if ($totalWeight == 0) {
                            // Jika tidak ada bobot, buat bobot merata
                            $categories = \App\Models\Category::all();
                            foreach ($categories as $category) {
                                $weights[$category->id] = 100 / $categories->count();
                            }
                            $totalWeight = 100;
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
                                    $normalizedWeight = $totalWeight > 0 ? $weight / $totalWeight : 0;
                                    
                                    // Pastikan nilai valid
                                    $value = max(1, intval($rating->value));
                                    
                                    // Bonus nilai jika kriteria dipilih
                                    if (isset($criterias[$rating->category_id]) && 
                                        $criterias[$rating->category_id] == $rating->subcategory_id) {
                                        $value = 5; // Nilai maksimal
                                    }
                                    
                                    // Hitung skor
                                    $ratingScore = $normalizedWeight * $value;
                                    $score += $ratingScore;
                                    
                                    $debugOutput .= "&nbsp; " . $rating->category_name . ": " .
                                        "Weight=" . number_format($normalizedWeight * 100, 1) . "%, " .
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
                                }
                            }
                            
                            $debugOutput .= "Total Score: " . number_format($score, 2) . "</div>";
                            
                            $cafeScores[$cafe->id] = [
                                'score' => $score,
                                'debug' => $debugInfo
                            ];
                        }
                        
                        // Urutkan cafe berdasarkan skor tertinggi
                        uasort($cafeScores, function($a, $b) {
                            return $b['score'] <=> $a['score'];
                        });
                        
                        // Ambil id cafe yang sudah diurutkan
                        $rankedCafeIds = array_keys($cafeScores);
                        
                        // Urutkan koleksi cafe sesuai skor
                        $rankedCafes = $cafes->sortBy(function($cafe) use ($rankedCafeIds) {
                            return array_search($cafe->id, $rankedCafeIds);
                        });
                    @endphp
                    
                    @if(count($selectedCriteria) > 0 || $lokasi)
                    <div class="mb-6 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Kriteria Pencarian:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedCriteria as $criteria)
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $criteria }}</span>
                            @endforeach
                            
                            @if($lokasi)
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Lokasi: {{ $lokasi }}</span>
                            @endif
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
                            @foreach($rankedCafes as $cafe)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-700">
                                    @if($cafe->gambar)
                                        <img src="{{ asset($cafe->gambar) }}" alt="{{ $cafe->nama }}" class="w-full h-52 object-cover">
                                    @else
                                        <div class="w-full h-52 bg-gradient-to-r from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $cafe->nama }}</h3>
                                        <p class="text-gray-600 dark:text-gray-300 mb-3 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1 1 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $cafe->lokasi }}
                                        </p>
                                        
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <div class="font-medium mb-1">Kriteria:</div>
                                                <ul class="space-y-1">
                                                    @foreach($cafe->ratings as $rating)
                                                        <li class="flex justify-between">
                                                            <span class="font-medium">{{ $rating->category->name }}:</span> 
                                                            <!-- <span>{{ $rating->subcategory->name }}  -->
                                                                <span class="text-yellow-500 ml-1">
                                                                    @for ($i = 0; $i < $rating->subcategory->value; $i++)
                                                                        ★
                                                                    @endfor
                                                                    @for ($i = $rating->subcategory->value; $i < 5; $i++)
                                                                        ☆
                                                                    @endfor
                                                                </span>
                                                            <!-- </span> -->
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Tambahkan detail perhitungan skor -->
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <details class="group">
                                                    <summary class="font-medium mb-1 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 flex items-center">
                                                        <span>Detail Perhitungan SMART</span>
                                                        <svg class="w-4 h-4 ml-1.5 transform transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </summary>
                                                    <div class="mt-2 pl-2 text-xs bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                                        @if(isset($cafeScores[$cafe->id]['debug']) && !empty($cafeScores[$cafe->id]['debug']))
                                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                                    <tr class="border-b dark:border-gray-700">
                                                                        <th class="text-left py-1 px-2">Kriteria</th>
                                                                        <th class="text-right py-1 px-2">Bobot (%)</th>
                                                                        <th class="text-left py-1 px-2">Subkategori</th>
                                                                        <th class="text-right py-1 px-2">Nilai</th>
                                                                        <th class="text-right py-1 px-2">Skor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                    @php $totalScore = 0; @endphp
                                                                    @foreach($cafeScores[$cafe->id]['debug'] as $category => $info)
                                                                        <tr>
                                                                            <td class="py-1 px-2">{{ $category }}</td>
                                                                            <td class="text-right py-1 px-2">{{ number_format($info['bobot_normal'] * 100, 0) }}%</td>
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
                                                Skor: {{ number_format($cafeScores[$cafe->id]['score'], 2) }}
                                            </div>
                                            <a href="#" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm font-medium flex items-center">
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