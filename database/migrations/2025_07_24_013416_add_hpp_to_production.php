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
        Schema::table('production', function (Blueprint $table) {
            $table->decimal('hpp_per_unit', 15, 2)->nullable()->after('biaya_overhead');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production', function (Blueprint $table) {
            //
        });
    }
};
