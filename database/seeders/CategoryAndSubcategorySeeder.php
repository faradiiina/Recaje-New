<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAndSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang sudah ada untuk menghindari duplikasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Subcategory::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Kategori 1: Harga
        $harga = Category::create([
            'name' => 'Harga'
        ]);

        // Subkategori Harga
        Subcategory::create([
            'category_id' => $harga->id,
            'name' => 'Sangat Murah (<10k/porsi)',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $harga->id,
            'name' => 'Murah (10k–15k)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $harga->id,
            'name' => 'Sedang (15k–25k)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $harga->id,
            'name' => 'Agak Mahal (25k–35k)',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $harga->id,
            'name' => 'Mahal (>35k)',
            'value' => 1
        ]);

        // Kategori 2: Kecepatan WiFi
        $wifi = Category::create([
            'name' => 'Kecepatan WiFi'
        ]);

        // Subkategori Kecepatan WiFi
        Subcategory::create([
            'category_id' => $wifi->id,
            'name' => 'Sangat Cepat (>50 Mbps)',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $wifi->id,
            'name' => 'Cepat (30–50 Mbps)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $wifi->id,
            'name' => 'Sedang (15–30 Mbps)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $wifi->id,
            'name' => 'Lambat (5–15 Mbps)',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $wifi->id,
            'name' => 'Sangat Lambat (<5 Mbps)',
            'value' => 1
        ]);

        // Kategori 3: Jam Operasional
        $jamOperasional = Category::create([
            'name' => 'Jam Operasional'
        ]);

        // Subkategori Jam Operasional
        Subcategory::create([
            'category_id' => $jamOperasional->id,
            'name' => '24 Jam',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $jamOperasional->id,
            'name' => '08.00–00.00 (16 jam)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $jamOperasional->id,
            'name' => '10.00–22.00 (12 jam)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $jamOperasional->id,
            'name' => '<10 jam operasional',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $jamOperasional->id,
            'name' => 'Tidak konsisten / Tidak tetap',
            'value' => 1
        ]);

        // Kategori 4: Fotogenik
        $fotogenik = Category::create([
            'name' => 'Fotogenik'
        ]);

        // Subkategori Fotogenik
        Subcategory::create([
            'category_id' => $fotogenik->id,
            'name' => 'Sangat Fotogenik (Desain unik, banyak spot foto)',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $fotogenik->id,
            'name' => 'Fotogenik (Desain bagus, cukup spot foto)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $fotogenik->id,
            'name' => 'Cukup Fotogenik (estetis biasa saja)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $fotogenik->id,
            'name' => 'Kurang Fotogenik',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $fotogenik->id,
            'name' => 'Tidak Fotogenik',
            'value' => 1
        ]);

        // Kategori 5: Area (Luas Tempat)
        $area = Category::create([
            'name' => 'Area (Outdoor/Indoor)'
        ]);

        Subcategory::create([
            'category_id' => $area->id,
            'name' => 'Outdoor & Indoor',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $area->id,
            'name' => 'Indoor',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $area->id,
            'name' => 'Outdoor',
            'value' => 1
        ]);

        // Kategori 6: Jarak dengan Kampus
        $jarak = Category::create([
            'name' => 'Jarak dengan Kampus'
        ]);

        // Subkategori Jarak dengan Kampus
        Subcategory::create([
            'category_id' => $jarak->id,
            'name' => 'Sangat Dekat (≤1 Km)',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $jarak->id,
            'name' => 'Dekat (1–2 Km)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $jarak->id,
            'name' => 'Sedang (2–5 Km)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $jarak->id,
            'name' => 'Jauh (5–10 Km)',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $jarak->id,
            'name' => 'Sangat Jauh (>10 Km)',
            'value' => 1
        ]);

        // Kategori 7: Fasilitas
        $fasilitas = Category::create([
            'name' => 'Fasilitas'
        ]);

        // Subkategori Fasilitas
        Subcategory::create([
            'category_id' => $fasilitas->id,
            'name' => 'Sangat Lengkap (5 fasilitas)',
            'value' => 5
        ]);
        Subcategory::create([
            'category_id' => $fasilitas->id,
            'name' => 'Lengkap (4 fasilitas)',
            'value' => 4
        ]);
        Subcategory::create([
            'category_id' => $fasilitas->id,
            'name' => 'Cukup (3 fasilitas)',
            'value' => 3
        ]);
        Subcategory::create([
            'category_id' => $fasilitas->id,
            'name' => 'Kurang (2 fasilitas)',
            'value' => 2
        ]);
        Subcategory::create([
            'category_id' => $fasilitas->id,
            'name' => 'Minim / Tidak Lengkap (0–1 fasilitas)',
            'value' => 1
        ]);
    }
} 