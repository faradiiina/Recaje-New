<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartSearchController extends Controller
{
    /**
     * Display the search form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currentStep = 1;
        return view('cafe.search-fixed', compact('currentStep'));
    }

    /**
     * Process the search form and display results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $currentStep = 3;
        $weights = $request->input('weight', []);
        $criterias = $request->input('criteria', []);
        $lokasi = $request->input('lokasi');

        // Get cafes with ratings
        $cafes = Cafe::with(['ratings.subcategory', 'ratings.category'])->get();
        
        // Filter berdasarkan lokasi jika ada
        if ($lokasi) {
            $cafes = $cafes->filter(function($cafe) use ($lokasi) {
                return stripos($cafe->lokasi, $lokasi) !== false;
            });
        }
        
        // Hitung total semua bobot untuk normalisasi
        $totalWeight = array_sum($weights);
        if ($totalWeight == 0) {
            // Jika tidak ada bobot, buat bobot merata
            $categories = Category::all();
            foreach ($categories as $category) {
                $weights[$category->id] = 100 / $categories->count();
            }
            $totalWeight = 100;
        }
        
        // Hitung skor SMART untuk setiap cafe
        $cafeScores = [];
        
        foreach ($cafes as $cafe) {
            $score = 0;
            $debugInfo = [];
            
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
                    
                    // Hitung utilitas - menerapkan konsep yang sama dengan view
                    $utilityValue = ($value - 1) / 4; // Normalisasi ke rentang 0-1 (jika nilai antara 1-5)
                    $ratingScore = $normalizedWeight * $utilityValue;
                    $score += $ratingScore;
                    
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
        
        // Ambil hanya 10 cafe teratas
        $rankedCafes = $rankedCafes->take(10);

        // Persiapkan data hasil untuk disimpan
        $resultsData = [];
        foreach ($rankedCafes as $cafe) {
            if (isset($cafeScores[$cafe->id])) {
                $resultsData[$cafe->id] = [
                    'name' => $cafe->nama,
                    'score' => $cafeScores[$cafe->id]['score'],
                    'details' => $cafeScores[$cafe->id]['debug']
                ];
            }
        }

        // Simpan hasil pencarian ke database jika user sudah login
        if (Auth::check()) {
            SearchHistory::create([
                'user_id' => Auth::id(),
                'weights' => $weights,
                'criteria' => $criterias,
                'location' => $lokasi,
                'results' => $resultsData
            ]);
        }

        return view('cafe.search-fixed', compact('currentStep', 'rankedCafes', 'cafeScores'));
    }

    /**
     * Display search history for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $histories = SearchHistory::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cafe.search-history', compact('histories'));
    }

    /**
     * Display search history detail.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $history = SearchHistory::where('user_id', Auth::id())
            ->findOrFail($id);

        // Retrieve cafes from the stored results
        $cafeIds = array_keys($history->results ?? []);
        $cafes = Cafe::whereIn('id', $cafeIds)->get();
        
        // Map cafes to include their scores from history
        $rankedCafes = $cafes->map(function($cafe) use ($history) {
            $cafe->smart_score = $history->results[$cafe->id]['score'] ?? 0;
            $cafe->smart_details = $history->results[$cafe->id]['details'] ?? [];
            return $cafe;
        })->sortByDesc('smart_score');
        
        $weights = $history->weights;
        
        return view('cafe.search-detail', compact('history', 'rankedCafes', 'weights'));
    }
} 