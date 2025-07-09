<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cafe;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;

class CafeController extends Controller
{
    /**
     * Tampilkan halaman pencarian cafe dengan stepper
     */
    public function search(Request $request)
    {
        // Tentukan langkah saat ini berdasarkan parameter URL
        $currentStep = 1;
        if ($request->has('weight')) {
            $currentStep = 3; // Hasil
        } elseif ($request->has('criteria')) {
            $currentStep = 2; // Kriteria
        }
        
        return view('cafe.search-fixed', compact('currentStep'));
    }

    /**
     * Tampilkan halaman rekomendasi cafe
     */
    public function recommend()
    {
        return view('cafe.recommend');
    }

    /**
     * Tampilkan semua cafe dengan fitur fuzzy search toleran terhadap typo
     */
    public function index(Request $request)
    {
        $query = Cafe::with(['ratings.subcategory', 'ratings.category']);
        
        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            
            // Memecah kata kunci pencarian menjadi beberapa kata
            $keywords = explode(' ', $searchTerm);
            
            $query->where(function($q) use ($searchTerm, $keywords) {
                // Exact match dengan prioritas tertinggi
                $q->where('nama', 'LIKE', "%{$searchTerm}%");
                
                // Pencarian fuzzy untuk masing-masing kata kunci
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) >= 3) {
                        // Membuat variasi kata kunci untuk toleransi typo
                        $q->orWhere('nama', 'LIKE', "%{$keyword}%");
                        
                        // Tambahkan wildcard di tengah untuk toleransi kesalahan ketik 1 karakter
                        for ($i = 0; $i < strlen($keyword) - 1; $i++) {
                            $fuzzyWord = substr($keyword, 0, $i) . '_' . substr($keyword, $i + 1);
                            $q->orWhere('nama', 'LIKE', "%{$fuzzyWord}%");
                        }
                    }
                }
                
                // Mencoba juga pencarian dengan operator SOUNDS LIKE jika tersedia
                try {
                    $q->orWhereRaw("SOUNDEX(nama) = SOUNDEX(?)", [$searchTerm]);
                } catch (\Exception $e) {
                    // SOUNDEX mungkin tidak tersedia, tidak perlu error handling
                }
            });
        }
        
        $cafes = $query->latest()->paginate(12)->withQueryString();
        
        return view('cafe.index', compact('cafes'));
    }
    
    /**
     * Tampilkan detail cafe
     */
    public function show($id)
    {
        $cafe = Cafe::with(['ratings.subcategory', 'ratings.category'])->findOrFail($id);
        return view('cafe.show', compact('cafe'));
    }
} 