<?php

namespace Database\Seeders;

use App\Models\Cafe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CafePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data harga berdasarkan ID cafe yang sudah ada
        $priceData = [
            1 => ['harga_termurah' => 18000, 'harga_termahal' => 25000], // Poppins
            2 => ['harga_termurah' => 10000, 'harga_termahal' => 18000], // Teras JTI
            3 => ['harga_termurah' => 10000, 'harga_termahal' => 20000], // Nugas Jember
            4 => ['harga_termurah' => 4000, 'harga_termahal' => 18000],  // Kopi Kampus
            5 => ['harga_termurah' => 15000, 'harga_termahal' => 40000], // Eterno
            7 => ['harga_termurah' => 8000, 'harga_termahal' => 20000],  // Fifty-Fifty
            8 => ['harga_termurah' => 10000, 'harga_termahal' => 35000], // Discuss Space & Coffee
            9 => ['harga_termurah' => 12000, 'harga_termahal' => 34000], // Subur
            10 => ['harga_termurah' => 6000, 'harga_termahal' => 15000], // Nuansa
            11 => ['harga_termurah' => 6000, 'harga_termahal' => 8000],  // Nol Kilometer
            13 => ['harga_termurah' => 6000, 'harga_termahal' => 25000], // Wafa
            14 => ['harga_termurah' => 15000, 'harga_termahal' => 45000], // 888
            15 => ['harga_termurah' => 7000, 'harga_termahal' => 25000], // Sorai
            16 => ['harga_termurah' => 8000, 'harga_termahal' => 23000], // Tharuh
            17 => ['harga_termurah' => 12000, 'harga_termahal' => 38000], // Contact
            18 => ['harga_termurah' => 14000, 'harga_termahal' => 20000], // Cus Cus
            19 => ['harga_termurah' => 18000, 'harga_termahal' => 34000], // Grufi
            20 => ['harga_termurah' => 15000, 'harga_termahal' => 25000], // Tomoro
            21 => ['harga_termurah' => 22000, 'harga_termahal' => 40000], // Fore
            22 => ['harga_termurah' => 1500, 'harga_termahal' => 37000],  // Fox
            23 => ['harga_termurah' => 12000, 'harga_termahal' => 45000], // Perasa
            24 => ['harga_termurah' => 5000, 'harga_termahal' => 15000],  // Kopi Boss
            25 => ['harga_termurah' => 10000, 'harga_termahal' => 30000], // Navas
            26 => ['harga_termurah' => 15000, 'harga_termahal' => 35000], // Kattappa (ID 26)
            28 => ['harga_termurah' => 17000, 'harga_termahal' => 47000], // Tanaloka (ID 28)
        ];

        // Update harga untuk setiap cafe
        foreach ($priceData as $cafeId => $prices) {
            $cafe = Cafe::find($cafeId);
            
            if ($cafe) {
                $cafe->update([
                    'harga_termurah' => $prices['harga_termurah'],
                    'harga_termahal' => $prices['harga_termahal']
                ]);
                
                echo "Updated cafe ID {$cafeId} ({$cafe->nama}): {$prices['harga_termurah']} - {$prices['harga_termahal']}\n";
            } else {
                echo "Cafe with ID {$cafeId} not found\n";
            }
        }
    }
}
