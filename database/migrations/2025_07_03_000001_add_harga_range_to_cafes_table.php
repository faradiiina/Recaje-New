<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->decimal('harga_termurah', 10, 2)->nullable()->after('area');
            $table->decimal('harga_termahal', 10, 2)->nullable()->after('harga_termurah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->dropColumn(['harga_termurah', 'harga_termahal']);
        });
    }
};
