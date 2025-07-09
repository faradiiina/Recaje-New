<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('categories')->truncate();

        // Data kategori dengan subkategori
        $categories = [
            [
                'name' => 'Jarak Kampus',
                'subcategory1' => 'Sangat Dekat',
                'subcategory2' => 'Dekat',
                'subcategory3' => 'Sedang',
                'subcategory4' => 'Jauh',
                'subcategory5' => 'Sangat Jauh',
                'value1' => 5,
                'value2' => 4,
                'value3' => 3,
                'value4' => 2,
                'value5' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kisaran Harga',
                'subcategory1' => 'Sangat Murah',
                'subcategory2' => 'Murah',
                'subcategory3' => 'Sedang',
                'subcategory4' => 'Mahal',
                'subcategory5' => 'Sangat Mahal',
                'value1' => 5,
                'value2' => 4,
                'value3' => 3,
                'value4' => 2,
                'value5' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fasilitas',
                'subcategory1' => 'Sangat Lengkap',
                'subcategory2' => 'Lengkap',
                'subcategory3' => 'Cukup',
                'subcategory4' => 'Kurang',
                'subcategory5' => 'Sangat Kurang',
                'value1' => 5,
                'value2' => 4,
                'value3' => 3,
                'value4' => 2,
                'value5' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kecepatan WiFi',
                'subcategory1' => 'Sangat Cepat',
                'subcategory2' => 'Cepat',
                'subcategory3' => 'Sedang',
                'subcategory4' => 'Lambat',
                'subcategory5' => 'Sangat Lambat',
                'value1' => 5,
                'value2' => 4,
                'value3' => 3,
                'value4' => 2,
                'value5' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert data kategori
        DB::table('categories')->insert($categories);

        $this->command->info('Categories seeded successfully!');
    }
} 