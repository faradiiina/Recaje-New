<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Langkah 1: Drop tabel cafe_ratings dahulu karena memiliki foreign key ke cafes
        Schema::dropIfExists('cafe_ratings');
        
        // Langkah 2: Buat tabel cafes_temp dengan struktur baru
        Schema::create('cafes_temp', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->string('gambar')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
        
        // Langkah 3: Pindahkan data dari cafes ke cafes_temp
        DB::statement('INSERT INTO cafes_temp (id, nama, lokasi, gambar, latitude, longitude, created_at, updated_at) 
                       SELECT id, nama, lokasi, gambar, latitude, longitude, created_at, updated_at FROM cafes');
        
        // Langkah 4: Drop tabel cafes lama
        Schema::dropIfExists('cafes');
        
        // Langkah 5: Rename cafes_temp menjadi cafes
        Schema::rename('cafes_temp', 'cafes');
        
        // Langkah 6: Buat kembali tabel cafe_ratings dengan struktur baru
        Schema::create('cafe_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tak perlu diimplementasikan karena ini adalah migrasi restrukturisasi
        // Jika ingin rollback, perlu restore manual dari backup
    }
}; 