<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cafe;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\CafeRating;
use App\Models\CafeImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cafes = Cafe::latest()->paginate(10);
        return view('admin.cafes.index', compact('cafes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.cafes.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'area' => 'nullable|array',
            'area.*' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'koordinat' => 'required|string',
            'harga_termurah' => 'nullable|numeric|min:0',
            'harga_termahal' => 'nullable|numeric|min:0|gte:harga_termurah',
            'category_ratings' => 'required|array',
            'category_ratings.*' => 'required|exists:subcategories,id',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            
            $data = [
                'nama' => $request->nama,
                'lokasi' => $request->koordinat,
                'area' => $request->area ? implode(', ', $request->area) : null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'harga_termurah' => $request->harga_termurah,
                'harga_termahal' => $request->harga_termahal,
            ];
            
            // Handle koordinat field if it exists and latitude/longitude are not set
            if ($request->filled('koordinat') && (!$request->filled('latitude') || !$request->filled('longitude'))) {
                $koordinat = $request->koordinat;
                $koordinatArray = explode(',', $koordinat);
                
                if (count($koordinatArray) === 2) {
                    $latitude = trim($koordinatArray[0]);
                    $longitude = trim($koordinatArray[1]);
                    
                    if (is_numeric($latitude) && is_numeric($longitude)) {
                        $data['latitude'] = $latitude;
                        $data['longitude'] = $longitude;
                    }
                }
            }
            
            // Buat cafe
            $cafe = Cafe::create($data);
            
            // Untuk menyimpan gambar pertama yang akan digunakan di kolom gambar
            $firstImagePath = null;
            
            // Handle multiple image upload
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $index => $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $imagePath = 'images/cafes/' . $imageName;
                    $image->move(public_path('images/cafes'), $imageName);
                    
                    CafeImage::create([
                        'cafe_id' => $cafe->id,
                        'image_path' => $imagePath
                    ]);
                    
                    // Simpan path gambar pertama
                    if ($index === 0) {
                        $firstImagePath = $imagePath;
                    }
                }
                
                // Update kolom gambar di tabel cafe dengan gambar pertama
                if ($firstImagePath) {
                    $cafe->gambar = $firstImagePath;
                    $cafe->save();
                }
            }
            
            // Simpan rating category
            if ($request->has('category_ratings')) {
                foreach ($request->category_ratings as $categoryId => $subcategoryId) {
                    $subcategory = Subcategory::findOrFail($subcategoryId);
                    
                    CafeRating::create([
                        'cafe_id' => $cafe->id,
                        'category_id' => $categoryId,
                        'subcategory_id' => $subcategoryId
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.cafes.index')
                ->with('success', 'Cafe berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cafe $cafe)
    {
        return view('admin.cafes.show', compact('cafe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cafe $cafe)
    {
        // Tambahkan ini untuk debugging
        \Log::info('Old Input Session:', session()->get('_old_input', []));
        \Log::info('Direct from DB: Lat=' . $cafe->latitude . ', Lng=' . $cafe->longitude);
        
        // Hapus semua data yang tersimpan di session untuk cafe ini
        session()->forget('_old_input');
        session()->forget("_cafe_{$cafe->id}_coordinates");
        
        // Paksa untuk mengambil data cafe langsung dari database
        $cafe = Cafe::where('id', $cafe->id)->first();
        
        // Simpan timestamp untuk tracking cache
        $timestamp = time();
        
        // Tambahan debug info
        \Log::info("Fetching fresh data for cafe {$cafe->id}, Timestamp: {$timestamp}");
        \Log::info("Fresh coordinates: Lat={$cafe->latitude}, Lng={$cafe->longitude}");
        
        // Buat koordinat langsung tanpa old()
        return view('admin.cafes.edit', [
            'cafe' => $cafe,
            'timestamp' => $timestamp,
            'categories' => Category::all(),
            'subcategories' => Subcategory::all(),
            'cafeRatings' => $cafe->ratings()->pluck('subcategory_id', 'category_id')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cafe $cafe)
    {
        $request->validate([
            'nama' => 'required',
            'area' => 'nullable|array',
            'area.*' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'koordinat' => 'required|string',
            'harga_termurah' => 'nullable|numeric|min:0',
            'harga_termahal' => 'nullable|numeric|min:0|gte:harga_termurah',
            'category_ratings' => 'required|array',
            'category_ratings.*' => 'required|exists:subcategories,id',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            
            // Debug: nilai sebelum update
            \Log::info("BEFORE UPDATE - ID: {$cafe->id}, Lokasi: {$cafe->lokasi}, Latitude: {$cafe->latitude}, Longitude: {$cafe->longitude}");
            \Log::info("INPUT VALUES - Koordinat: {$request->koordinat}, Latitude: {$request->latitude}, Longitude: {$request->longitude}");
            
            // Ensure koordinat, latitude and longitude are in sync
            // Parse koordinat to extract latitude and longitude
            $koordinatArray = explode(',', $request->koordinat);
            if (count($koordinatArray) === 2) {
                $latitude = trim($koordinatArray[0]);
                $longitude = trim($koordinatArray[1]);
                
                // Verify values are numeric
                if (is_numeric($latitude) && is_numeric($longitude)) {
                    $data = [
                        'nama' => $request->nama,
                        'lokasi' => $request->koordinat,
                        'area' => $request->area ? implode(', ', $request->area) : null,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'harga_termurah' => $request->harga_termurah,
                        'harga_termahal' => $request->harga_termahal,
                    ];
                    
                    // Force sync latitude/longitude from koordinat
                    $request->merge([
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ]);
                } else {
                    // Use provided latitude/longitude if parsing failed
                    $data = [
                        'nama' => $request->nama,
                        'lokasi' => $request->koordinat,
                        'area' => $request->area ? implode(', ', $request->area) : null,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'harga_termurah' => $request->harga_termurah,
                        'harga_termahal' => $request->harga_termahal,
                    ];
                }
            } else {
                // Use provided latitude/longitude if parsing failed
                $data = [
                    'nama' => $request->nama,
                    'lokasi' => $request->koordinat,
                    'area' => $request->area ? implode(', ', $request->area) : null,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'harga_termurah' => $request->harga_termurah,
                    'harga_termahal' => $request->harga_termahal,
                ];
            }
            
            // Update cafe
            $cafe->update($data);
            
            // Verify update success by re-fetching from DB
            $updatedCafe = Cafe::find($cafe->id);
            \Log::info("AFTER UPDATE - ID: {$updatedCafe->id}, Lokasi: {$updatedCafe->lokasi}, Latitude: {$updatedCafe->latitude}, Longitude: {$updatedCafe->longitude}");
            
            // Flag untuk menandai apakah ada gambar baru yang ditambahkan
            $newImagesAdded = false;
            
            // Handle multiple image upload
            if ($request->hasFile('gambar')) {
                $newImagesAdded = true;
                foreach ($request->file('gambar') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                    $imagePath = 'images/cafes/' . $imageName;
                    $image->move(public_path('images/cafes'), $imageName);
                    
                    CafeImage::create([
                        'cafe_id' => $cafe->id,
                        'image_path' => $imagePath
                    ]);
                }
            }
            
            // Hapus rating lama
            $cafe->ratings()->delete();
            
            // Simpan rating category baru
            if ($request->has('category_ratings')) {
                foreach ($request->category_ratings as $categoryId => $subcategoryId) {
                    $subcategory = Subcategory::findOrFail($subcategoryId);
                    
                    CafeRating::create([
                        'cafe_id' => $cafe->id,
                        'category_id' => $categoryId,
                        'subcategory_id' => $subcategoryId
                    ]);
                }
            }
            
            // Update kolom gambar di tabel cafe jika gambar baru ditambahkan
            // atau jika kolom gambar masih kosong
            if ($newImagesAdded || empty($cafe->gambar)) {
                $this->updateCafeMainImage($cafe->id);
            }
            
            DB::commit();
            
            return redirect()->route('admin.cafes.index')
                ->with('success', 'Cafe berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cafe $cafe)
    {
        $cafe->delete();

        return redirect()->route('admin.cafes.index')
            ->with('success', 'Cafe berhasil dihapus!');
    }

    /**
     * Delete cafe image
     */
    public function deleteImage($id)
    {
        try {
            // Cari gambar berdasarkan ID
            $image = CafeImage::findOrFail($id);
            $cafeId = $image->cafe_id;
            
            // Hapus file fisik jika ada
            if (file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            } else {
                // Log jika file tidak ditemukan
                \Log::warning("File tidak ditemukan: {$image->image_path}");
            }
            
            // Hapus record dari database
            $image->delete();
            
            // Jika gambar yang dihapus adalah gambar utama, update kolom gambar di tabel cafes
            $cafe = Cafe::find($cafeId);
            if ($cafe && $cafe->gambar === $image->image_path) {
                $this->updateCafeMainImage($cafeId);
            }
            
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error("Error menghapus gambar: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Update main image in cafes table with the image that has the smallest ID
     */
    private function updateCafeMainImage($cafeId)
    {
        // Ambil gambar dengan ID terkecil dari tabel cafe_images
        $firstImage = CafeImage::where('cafe_id', $cafeId)
                             ->orderBy('id', 'asc')
                             ->first();
        
        $cafe = Cafe::find($cafeId);
        if ($cafe) {
            if ($firstImage) {
                // Update kolom gambar dengan path gambar yang memiliki ID terkecil
                $cafe->gambar = $firstImage->image_path;
            } else {
                // Jika tidak ada gambar, kosongkan kolom gambar
                $cafe->gambar = null;
            }
            $cafe->save();
        }
    }

    /**
     * Update image order
     */
    public function updateImageOrder(Request $request)
    {
        try {
            // Ambil data dari request, baik berupa form reguler atau JSON
            if ($request->isJson() || $request->expectsJson() || $request->header('Content-Type') === 'application/json') {
                // Untuk request JSON (fetch API)
                $data = $request->json()->all();
                $cafeId = $data['cafe_id'] ?? null;
                $imageIds = $data['image_ids'] ?? [];
            } else {
                // Untuk request form biasa (jQuery Ajax)
                $cafeId = $request->input('cafe_id');
                $imageIds = $request->input('image_ids', []);
            }
            
            // Validasi Data
            if (!$cafeId || empty($imageIds)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data cafe_id dan image_ids diperlukan'
                ], 400);
            }
            
            // Log untuk debugging
            \Log::info('Updating image order for cafe: ' . $cafeId);
            \Log::info('Image IDs: ' . print_r($imageIds, true));
            
            // Perbarui urutan (sort_order) gambar
            foreach ($imageIds as $index => $imageId) {
                CafeImage::where('id', $imageId)
                    ->update(['sort_order' => $index + 1]); // +1 agar urutan dimulai dari 1
            }
            
            // Perbarui gambar utama cafe (gunakan gambar pertama sebagai gambar utama)
            if (!empty($imageIds)) {
                $firstImageId = $imageIds[0];
                $firstImage = CafeImage::find($firstImageId);
                
                if ($firstImage) {
                    $cafe = Cafe::find($cafeId);
                    if ($cafe) {
                        $cafe->gambar = $firstImage->image_path;
                        $cafe->save();
                    }
                }
            }
            
            return response()->json(['success' => true, 'message' => 'Urutan gambar berhasil diperbarui']);
        } catch (\Exception $e) {
            \Log::error("Error mengatur urutan gambar: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fix coordinates from lokasi field
     */
    public function fixCoordinates($id)
    {
        try {
            // Cari cafe berdasarkan ID
            $cafe = Cafe::findOrFail($id);
            
            // Ambil nilai lokasi
            $lokasi = $cafe->lokasi;
            
            // Parsing lokasi untuk mendapatkan latitude dan longitude
            $koordinatArray = explode(',', $lokasi);
            
            if (count($koordinatArray) === 2) {
                $latitude = trim($koordinatArray[0]);
                $longitude = trim($koordinatArray[1]);
                
                // Verify values are numeric
                if (is_numeric($latitude) && is_numeric($longitude)) {
                    // Update nilai latitude dan longitude
                    $cafe->latitude = $latitude;
                    $cafe->longitude = $longitude;
                    $cafe->save();
                    
                    \Log::info("Fixed coordinates - ID: {$cafe->id}, Lokasi: {$lokasi}, New Lat: {$latitude}, New Lng: {$longitude}");
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Koordinat berhasil diperbaiki',
                        'data' => [
                            'id' => $cafe->id,
                            'latitude' => $cafe->latitude,
                            'longitude' => $cafe->longitude,
                            'lokasi' => $cafe->lokasi
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nilai latitude/longitude tidak valid (bukan angka)'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Format lokasi tidak valid, seharusnya "latitude, longitude"'
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error("Error fixing coordinates: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
