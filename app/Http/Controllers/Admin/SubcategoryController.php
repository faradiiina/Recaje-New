<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::paginate(10);
        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $selectedCategoryId = request('category_id');
        return view('admin.subcategories.create', compact('categories', 'selectedCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Request data:', $request->all());
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategories' => 'required|array|min:1',
            'subcategories.*.name' => 'required|string|max:255',
            'subcategories.*.value' => 'required|integer|min:1|max:5'
        ]);

        \Illuminate\Support\Facades\Log::info('Validation passed, processing subcategories');
        
        try {
            DB::beginTransaction();
            
            foreach ($request->subcategories as $subcategory) {
                \Illuminate\Support\Facades\Log::info('Creating subcategory:', $subcategory);
                
                Subcategory::create([
                    'category_id' => $request->category_id,
                    'name' => $subcategory['name'],
                    'value' => $subcategory['value']
                ]);
            }
            
            DB::commit();
            \Illuminate\Support\Facades\Log::info('All subcategories created successfully');
            
            return redirect()->route('admin.subcategories.index')
                ->with('success', 'Sub-kategori berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error creating subcategories: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat menambahkan sub-kategori: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return view('admin.subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub-kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub-kategori berhasil dihapus!');
    }
} 