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
        Schema::create('production', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('produk');
            $table->integer('jumlah_produksi');
            $table->decimal('total_biaya_bahan', 15, 2);
            $table->decimal('biaya_tenaga_kerja', 15, 2);
            $table->decimal('biaya_overhead', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production');
    }
};
