<?php

namespace Database\Seeders;

use App\Models\Cafe;
use App\Models\CafeRating;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CafeAndRatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data cafe dan rating yang sudah ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        CafeRating::truncate();
        Cafe::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Data cafe dengan rating spesifik untuk setiap kategori
        $cafes = [
            ['nama' => 'Poppins', 'lokasi' => null, 'harga' => 3, 'wifi' => 3, 'jam' => 5, 'fotogenik' => 4, 'jarak' => 3, 'fasilitas' => 3, 'area' => 'Indoor, Outdoor'],
            ['nama' => 'Teras JTI', 'lokasi' => null, 'harga' => 4, 'wifi' => 5, 'jam' => 3, 'fotogenik' => 3, 'jarak' => 5, 'fasilitas' => 4, 'area' => 'Indoor, Semi Outdoor, Outdoor'],
            ['nama' => 'Nugas Jember', 'lokasi' => null, 'harga' => 4, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 1, 'jarak' => 4, 'fasilitas' => 1, 'area' => 'Semi Outdoor'],
            ['nama' => 'Kopi Kampus', 'lokasi' => null, 'harga' => 4, 'wifi' => 3, 'jam' => 5, 'fotogenik' => 1, 'jarak' => 3, 'fasilitas' => 3, 'area' => 'Semi Outdoor'],
            ['nama' => 'Eterno', 'lokasi' => null, 'harga' => 2, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 4, 'jarak' => 3, 'fasilitas' => 5, 'area' => 'Indoor, Outdoor'],
            ['nama' => 'Kattappa', 'lokasi' => null, 'harga' => 3, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 4, 'jarak' => 3, 'fasilitas' => 4, 'area' => 'Indoor, Semi Outdoor, Outdoor'],
            ['nama' => 'Fifty-Fifty', 'lokasi' => null, 'harga' => 4, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 2, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Semi Outdoor'],
            ['nama' => 'Discuss Space & Coffee', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 4, 'area' => 'Indoor'],
            ['nama' => 'Subur', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 5, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor'],
            ['nama' => 'Nuansa', 'lokasi' => null, 'harga' => 4, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 2, 'jarak' => 3, 'fasilitas' => 5, 'area' => 'Indoor, Semi Outdoor'],
            ['nama' => 'Nol Kilometer', 'lokasi' => null, 'harga' => 5, 'wifi' => 2, 'jam' => 3, 'fotogenik' => 1, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Semi Outdoor'],
            ['nama' => 'Tanaloka', 'lokasi' => null, 'harga' => 2, 'wifi' => 4, 'jam' => 2, 'fotogenik' => 5, 'jarak' => 3, 'fasilitas' => 5, 'area' => 'Indoor, Semi Outdoor, Outdoor'],
            ['nama' => 'Wafa', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 2, 'fasilitas' => 5, 'area' => 'Indoor, Outdoor'],
            ['nama' => '888', 'lokasi' => null, 'harga' => 2, 'wifi' => 5, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor, Semi Outdoor'],
            ['nama' => 'Sorai', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 3, 'area' => 'Indoor, Outdoor'],
            ['nama' => 'Tharuh', 'lokasi' => null, 'harga' => 3, 'wifi' => 2, 'jam' => 5, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor, Semi Outdoor'],
            ['nama' => 'Contact', 'lokasi' => null, 'harga' => 3, 'wifi' => 2, 'jam' => 5, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor, Semi Outdoor, Outdoor'],
            ['nama' => 'Cus Cus', 'lokasi' => null, 'harga' => 3, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 4, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor, Outdoor'],
            ['nama' => 'Grufi', 'lokasi' => null, 'harga' => 2, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 5, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor'],
            ['nama' => 'Tomoro', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 4, 'fasilitas' => 4, 'area' => 'Indoor'],
            ['nama' => 'Fore', 'lokasi' => null, 'harga' => 2, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Indoor'],
            ['nama' => 'Fox', 'lokasi' => null, 'harga' => 3, 'wifi' => 3, 'jam' => 4, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 1, 'area' => 'Indoor'],
            ['nama' => 'Perasa', 'lokasi' => null, 'harga' => 2, 'wifi' => 3, 'jam' => 5, 'fotogenik' => 3, 'jarak' => 3, 'fasilitas' => 3, 'area' => 'Indoor'],
            ['nama' => 'Kopi Boss', 'lokasi' => null, 'harga' => 5, 'wifi' => 3, 'jam' => 5, 'fotogenik' => 1, 'jarak' => 3, 'fasilitas' => 3, 'area' => 'Semi Outdoor'],
            ['nama' => 'Navas', 'lokasi' => null, 'harga' => 3, 'wifi' => 4, 'jam' => 5, 'fotogenik' => 2, 'jarak' => 3, 'fasilitas' => 2, 'area' => 'Semi Outdoor'],
        ];

        // Get all categories and their subcategories
        $categories = Category::all();
        
        // Mendapatkan kategori berdasarkan namanya untuk memudahkan penugasan subcategory
        $hargaCategory = Category::where('name', 'Harga')->first();
        $wifiCategory = Category::where('name', 'Kecepatan WiFi')->first();
        $jamOperasionalCategory = Category::where('name', 'Jam Operasional')->first();
        $fotogenikCategory = Category::where('name', 'Fotogenik')->first();
        $areaCategory = Category::where('name', 'Area (Outdoor/Indoor)')->first();
        $jarakCategory = Category::where('name', 'Jarak dengan Kampus')->first();
        $fasilitasCategory = Category::where('name', 'Fasilitas')->first();
        
        // Membuat cafe dan ratings
        foreach ($cafes as $cafeData) {
            // Buat cafe dengan data dasar
            $cafe = Cafe::create([
                'nama' => $cafeData['nama'],
                'lokasi' => $cafeData['lokasi'],
                'area' => $cafeData['area'],
            ]);

            // Assign ratings berdasarkan data yang sudah ditentukan
            // Harga
            if ($hargaCategory) {
                $this->createRating($cafe->id, $hargaCategory->id, $cafeData['harga']);
            }
            
            // WiFi
            if ($wifiCategory) {
                $this->createRating($cafe->id, $wifiCategory->id, $cafeData['wifi']);
            }
            
            // Jam Operasional
            if ($jamOperasionalCategory) {
                $this->createRating($cafe->id, $jamOperasionalCategory->id, $cafeData['jam']);
            }
            
            // Fotogenik
            if ($fotogenikCategory) {
                $this->createRating($cafe->id, $fotogenikCategory->id, $cafeData['fotogenik']);
            }
            
            // Jarak
            if ($jarakCategory) {
                $this->createRating($cafe->id, $jarakCategory->id, $cafeData['jarak']);
            }
            
            // Fasilitas
            if ($fasilitasCategory) {
                $this->createRating($cafe->id, $fasilitasCategory->id, $cafeData['fasilitas']);
            }
        }
    }

    /**
     * Membuat rating berdasarkan category_id dan nilai
     */
    private function createRating($cafeId, $categoryId, $value)
    {
        // Cari subcategory yang sesuai dengan category_id dan value
        $subcategory = Subcategory::where('category_id', $categoryId)
                                ->where('value', $value)
                                ->first();
        
        if ($subcategory) {
            CafeRating::create([
                'cafe_id' => $cafeId,
                'category_id' => $categoryId,
                'subcategory_id' => $subcategory->id
            ]);
        }
    }
} 