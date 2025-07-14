// Inisialisasi variabel global
let hasSearchResults = false;
let currentStep = 1;

// Global data untuk menyimpan nilai penting setiap kriteria
const importanceValues = {};

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

// Fungsi inisialisasi yang akan dipanggil ketika DOM selesai dimuat
function initializeApp() {
    try {
        console.log('DOM Content Loaded.');
        
        // Ambil data dari atribut script
        const scriptTag = document.querySelector('script[src*="cafe-search.js"]');
        if (scriptTag) {
            hasSearchResults = scriptTag.getAttribute('data-has-search-results') === 'true';
            currentStep = parseInt(scriptTag.getAttribute('data-current-step') || '1');
        }
        
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
        
        // Tampilkan section sesuai langkah
        if (hasSearchResults) {
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
}

// Inisialisasi saat DOM selesai dimuat
document.addEventListener('DOMContentLoaded', initializeApp); 