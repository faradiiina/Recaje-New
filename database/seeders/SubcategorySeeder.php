<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('subcategories')->truncate();

        // Data subkategori berdasarkan kategori
        $subcategories = [
            'Jarak Kampus' => [
                'Sangat Dekat (< 1 km)',
                'Dekat (1-2 km)',
                'Sedang (2-3 km)',
                'Jauh (> 3 km)'
            ],
            'Kisaran Harga' => [
                'Sangat Murah (< Rp 10.000)',
                'Murah (Rp 10.000 - Rp 20.000)',
                'Sedang (Rp 20.000 - Rp 30.000)',
                'Mahal (> Rp 30.000)'
            ],
            'Fasilitas' => [
                'Sangat Lengkap',
                'Lengkap',
                'Cukup',
                'Minimal'
            ],
            'Kecepatan WiFi' => [
                'Sangat Cepat (> 50 Mbps)',
                'Cepat (30-50 Mbps)',
                'Sedang (10-30 Mbps)',
                'Lambat (< 10 Mbps)'
            ]
        ];

        // Insert data subkategori
        foreach ($subcategories as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();
            
            if ($category) {
                foreach ($items as $subcategoryName) {
                    Subcategory::create([
                        'category_id' => $category->id,
                        'name' => $subcategoryName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Subcategories seeded successfully!');
    }
} 