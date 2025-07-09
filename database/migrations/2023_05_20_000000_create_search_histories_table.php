<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('weights')->nullable(); // Menyimpan bobot kriteria
            $table->json('criteria')->nullable(); // Menyimpan kriteria yang dipilih
            $table->string('location')->nullable(); // Menyimpan lokasi jika ada
            $table->json('results')->nullable(); // Menyimpan hasil cafe dengan skor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_histories');
    }
} 