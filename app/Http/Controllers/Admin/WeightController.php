<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Weight;
use App\Models\Category;
use App\Models\Subcategory;

class WeightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        $weights = Weight::with(['category', 'subcategory'])->get();
        
        return view('admin.weights.index', compact('categories', 'weights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('subcategories')->get();
        $categoriesJson = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name
                    ];
                })
            ];
        });
        
        return view('admin.weights.create', [
            'categories' => $categories,
            'categoriesJson' => $categoriesJson
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'weight_value' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung total bobot untuk normalisasi
        $totalWeight = Weight::sum('weight_value');
        
        // Hitung bobot yang dinormalisasi
        $normalizedWeight = $request->weight_value / ($totalWeight + $request->weight_value);
        
        Weight::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'weight_value' => $request->weight_value,
            'normalized_weight' => $normalizedWeight
        ]);

        // Update semua bobot yang dinormalisasi
        $this->updateAllNormalizedWeights();

        return redirect()->route('admin.weights.index')
            ->with('success', 'Bobot berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Weight $weight)
    {
        $categories = Category::with('subcategories')->get();
        $categoriesJson = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name
                    ];
                })
            ];
        });
        
        return view('admin.weights.edit', [
            'weight' => $weight,
            'categories' => $categories,
            'categoriesJson' => $categoriesJson
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Weight $weight)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'weight_value' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung total bobot untuk normalisasi
        $totalWeight = Weight::where('id', '!=', $weight->id)->sum('weight_value');
        
        // Hitung bobot yang dinormalisasi
        $normalizedWeight = $request->weight_value / ($totalWeight + $request->weight_value);
        
        $weight->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'weight_value' => $request->weight_value,
            'normalized_weight' => $normalizedWeight
        ]);

        // Update semua bobot yang dinormalisasi
        $this->updateAllNormalizedWeights();

        return redirect()->route('admin.weights.index')
            ->with('success', 'Bobot berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Weight $weight)
    {
        $weight->delete();
        
        // Update semua bobot yang dinormalisasi
        $this->updateAllNormalizedWeights();

        return redirect()->route('admin.weights.index')
            ->with('success', 'Bobot berhasil dihapus!');
    }

    private function updateAllNormalizedWeights()
    {
        $weights = Weight::all();
        $totalWeight = $weights->sum('weight_value');
        
        foreach ($weights as $weight) {
            $weight->update([
                'normalized_weight' => $weight->weight_value / $totalWeight
            ]);
        }
    }
}
