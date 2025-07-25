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
        Schema::create('production_detail', function (Blueprint $table) {
           $table->id();
            $table->foreignId('production_id')->constrained('production')->onDelete('cascade');
            $table->foreignId('inventori_id')->constrained('inventori')->onDelete('cascade'); // bahan baku
            $table->integer('jumlah'); // berapa banyak yang digunakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_detail');
    }
};
