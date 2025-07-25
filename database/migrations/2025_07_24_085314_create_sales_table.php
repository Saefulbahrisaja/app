<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // tanggal penjualan
            $table->string('produk'); // nama produk
            $table->integer('jumlah'); // jumlah unit yang terjual
            $table->decimal('harga_satuan', 12, 2); // harga per unit
            $table->text('keterangan')->nullable(); // opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
