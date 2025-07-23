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
        Schema::create('hpp_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('production')->onDelete('cascade');
            $table->decimal('total_biaya', 15, 2);
            $table->integer('jumlah_unit');
            $table->decimal('hpp_per_unit', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpp_log');
    }
};
